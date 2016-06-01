#!/usr/bin/env python
#coding=utf-8

import db

import re
import time
import thread
import pycurl
import urllib
import StringIO


DEBUG = True

TaskCount = 0

db.config.count_thread = len(db.config.poj_user)

TryResult = ['Running & Judging', 'Waiting', 'Compiling']

def getList(len):
	res = db.run_sql("select * from Record where `rid`='__' AND `oj`='POJ' AND `result`<>'Waiting' order by time DESC limit 0,%d" % len)
	return res

def Log(info):
	global DEBUG
	if DEBUG:
		print info

def CurlPOST(url, data, cookie):
	c = pycurl.Curl()
	b = StringIO.StringIO()
	c.setopt(pycurl.URL, url)
	c.setopt(pycurl.POST, 1)
	c.setopt(pycurl.WRITEFUNCTION, b.write)
	c.setopt(pycurl.COOKIEFILE, cookie)
	c.setopt(pycurl.COOKIEJAR, cookie)
	c.setopt(pycurl.POSTFIELDS, urllib.urlencode(data))
	c.perform()
	html = b.getvalue()
	b.close()
	c.close()
	return html
	
def CurlGET(url, cookie):
	c = pycurl.Curl()
	b = StringIO.StringIO()
	c.setopt(pycurl.URL, url)
	# c.setopt(pycurl.POST, 1)
	c.setopt(pycurl.WRITEFUNCTION, b.write)
	c.setopt(pycurl.COOKIEFILE, cookie)
	c.setopt(pycurl.COOKIEJAR, cookie)
	c.perform()
	html = b.getvalue()
	b.close()
	c.close()
	return html

def IsThisCode(local_id, run_id, cookie):
	html = CurlGET("http://poj.org/showsource?solution_id=%s" % run_id, cookie)
	match = re.findall('//&lt;ID&gt;%s&lt;/ID&gt;' % local_id, html)
	if len(match) != 0:
		return run_id
	else:
		return -1

def Worker(item, oj_user, oj_pass, index):
	global TaskCount, TryResult
	Log('[I] => Thread %d processing ...' % index)
	# Cookie file
	cookie = "/tmp/poj.org.cookie.%s" % item[0]
	# Login
	try:
		CurlPOST("http://poj.org/login", {
				"user_id1" : oj_user,
				"password1" : oj_pass,
				"url" : "/"
			}, cookie)
	except Exception,e:
		Log('[E] => Login failed !')
		TaskCount -= 1
		db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
		db.config.poj_user[index][2] = False
		thread.exit_thread()
	# Post code
	try:
		CurlPOST("http://poj.org/submit", {
				"problem_id" : item[3],
				"language" : item[8],
				"encoded" : "1",
				"source" : item[13]
			}, cookie)
	except Exception,e:
		Log('[E] => Submit failed !')
		TaskCount -= 1
		db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
		db.config.poj_user[index][2] = False
		thread.exit_thread()
	# Get RunID
	try:
		html = CurlGET("http://poj.org/status?problem_id=%s&user_id=%s&result=&language=%s" % (item[3], oj_user, item[8]), cookie)
	except Exception,e:
		Log('[E] => Get RunID failed !')
		db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
		TaskCount -= 1
		db.config.poj_user[index][2] = False
		thread.exit_thread()
	# Get RunID List
	match = re.findall(r'<td>(\d+)</td>', html)
	RunID = -1
	try:
		for runid in match:
			TmpID = IsThisCode(item[0], runid, cookie)
			if TmpID != -1:
				RunID = TmpID
				break
	except Exception,e:
		Log('[E] => Get RunID failed !')
		db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
		TaskCount -= 1
		db.config.poj_user[index][2] = False
		thread.exit_thread()
	# No RunID, exit
	if RunID == -1:
		db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
		TaskCount -= 1
		db.config.poj_user[index][2] = False
		thread.exit_thread()
	# Match result
	match = re.findall(r"<tr align=center><td>%s</td><td>.*</td><td>.*</td><td>.*<font color=\w*>(.*)</font>.*</td><td>(.*)</td><td>(.*)</td><td><a href=.*>(.*)</a></td><td>.*</td><td>.*</td></tr>" % RunID, html)
	print match
	# No result, exit
	if len(match) <= 0:
		db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
		TaskCount -= 1
		db.config.poj_user[index][2] = False
		thread.exit_thread()
	result = list(match[0])
	# If not final result, try again
	Log('[I] => %s' % result)
	while result[0] in TryResult:
		Log('[I] => Try again ...')
		time.sleep(3)
		try:
			html = CurlGET("http://poj.org/status?problem_id=%s&user_id=%s&result=&language=%s" % (item[3], oj_user, item[8]), cookie)
		except Exception,e:
			db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
			TaskCount -= 1
			db.config.poj_user[index][2] = False
			thread.exit_thread()
		match = re.findall(r"<tr align=center><td>%s</td><td>.*</td><td>.*</td><td>.*<font color=\w*>(.*)</font>.*</td><td>(.*)</td><td>(.*)</td><td><a href=.*>(.*)</a></td><td>.*</td><td>.*</td></tr>" % RunID, html)
		print match
		if len(match) <= 0:
			db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
			TaskCount -= 1
			db.config.poj_user[index][2] = False
			thread.exit_thread()
		result = list(match[0])
		Log('[I] => Tried: %s' % result)
	# Fix result
	if result[1] == '':
		result[1] = '0K'
	if result[2] == '':
		result[2] = '0MS'
	# Update result
	db.run_sql("update Record set `rid`='%s',`memory`='%s',`long`='%s',`lang`='%s',`result`='%s' where `id`='%s'" % (RunID, result[1], result[2], result[3], result[0], item[0]))
	TaskCount -= 1
	db.config.poj_user[index][2] = False
	thread.exit_thread()

def main():
	global TaskCount
	while True:
		time.sleep(3)
		try:
			can = db.config.count_thread - TaskCount
			if can == 0:
				continue
			res = getList(can)
			for item in res:
				TaskCount += 1
				uu = []
				print db.config.poj_user
				for i in range(0, len(db.config.poj_user)):
					if db.config.poj_user[i][2] != True:
						uu = db.config.poj_user[i]
						db.config.poj_user[i][2] = True
						break
				db.run_sql("update Record set result='Waiting' where `id`='%s'" % str(item[0]))
				thread.start_new_thread(Worker, (item, uu[0], uu[1], i))
		except Exception,e:
			print e	


if __name__ == '__main__':
	main()
