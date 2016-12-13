#!/usr/bin/env python
#coding=utf-8

import db

import re
import os
import time
import thread
import pycurl
import urllib
import requests
import threading
import StringIO
import MySQLdb


DEBUG = True

TaskCount = 0

Mutex = threading.Lock()

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
    if cookie == '':
        response = requests.post(url, data)
    else:
        response = requests.post(url, data, cookies=cookie)
    return response.text, response.cookies
    
def CurlGET(url, cookie):
    if cookie== '':
        response = requests.post(url)
    else:
        response = requests.get(url, cookies=cookie)
    return response.text, response.cookies

def IsThisCode(local_id, run_id, cookie):
    html, t = CurlGET("http://poj.org/showsource?solution_id=%s" % run_id, cookie)
    match = re.findall('//&lt;ID&gt;%s&lt;/ID&gt;' % local_id, html)
    if len(match) != 0:
        return run_id
    else:
        return -1

def ExitThread(index, cookie):
    global TaskCount
    Mutex.acquire()
    TaskCount -= 1
    db.config.poj_user[index][2] = False
    Mutex.release()


def Worker(item, oj_user, oj_pass, index):
    global TaskCount, TryResult
    Log('[I] => Thread %d processing %s using %s ...' % (index, item[0], oj_user))
    # Set cookies
    cookie = ''
    # Login
    Log('[I] => Thread %d step %d ...' % (index, 1))
    try:
        t, cookie = CurlPOST("http://poj.org/login", {
                "user_id1" : oj_user,
                "password1" : oj_pass
            }, cookie)
    except Exception,e:
        Log('[E] => Login failed !' + str(e))
        db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
        ExitThread(index, cookie)
        thread.exit_thread()
    # Post code
    Log('[I] => Thread %d step %d ...' % (index, 2))
    time.sleep(1)
    try:
        CurlPOST("http://poj.org/submit", {
                "problem_id" : item[3],
                "language" : item[9],
                "encoded" : "1",
                "source" : item[14]
            }, cookie)
    except Exception,e:
        Log('[E] => Submit failed !' + str(e))
        db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
        ExitThread(index, cookie)
        thread.exit_thread()
    # Get RunID
    Log('[I] => Thread %d step %d ...' % (index, 3))
    time.sleep(1)
    try:
        html, t = CurlGET("http://poj.org/status?problem_id=%s&user_id=%s&result=&language=%s" % (item[3], oj_user, item[9]), cookie)
    except Exception,e:
        Log('[E] => Get RunID failed !' + str(e))
        db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
        ExitThread(index, cookie)
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
        Log('[E] => Get RunID failed !' + str(e))
        db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
        ExitThread(index, cookie)
        thread.exit_thread()
    # No RunID, exit
    if RunID == -1:
        db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
        ExitThread(index, cookie)
        thread.exit_thread()
    # Match result
    match = re.findall(r"<tr align=center><td>%s</td><td>.*</td><td>.*</td><td>.*<font color=\w*>(.*)</font>.*</td><td>(.*)</td><td>(.*)</td><td><a href=.*>(.*)</a></td><td>.*</td><td>.*</td></tr>" % RunID, html)
    # No result, exit
    if len(match) <= 0:
        db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
        ExitThread(index, cookie)
        thread.exit_thread()
    result = list(match[0])
    # If not final result, try again
    Log('[I] => %s' % result)
    while result[0] in TryResult:
        Log('[I] => Try again ...')
        time.sleep(3)
        try:
            html, t = CurlGET("http://poj.org/status?problem_id=%s&user_id=%s&result=&language=%s" % (item[3], oj_user, item[8]), cookie)
        except Exception,e:
            db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
            ExitThread(index, cookie)
            thread.exit_thread()
        match = re.findall(r"<tr align=center><td>%s</td><td>.*</td><td>.*</td><td>.*<font color=\w*>(.*)</font>.*</td><td>(.*)</td><td>(.*)</td><td><a href=.*>(.*)</a></td><td>.*</td><td>.*</td></tr>" % RunID, html)
        if len(match) <= 0:
            db.run_sql("update Record set `rid`='NONE',`memory`='0K',`long`='0MS',`lang`='Unknown',`result`='Submit Error' where `id`='%s'" % item[0])
            ExitThread(index, cookie)
            thread.exit_thread()
        result = list(match[0])
        Log('[I] => Tried: %s' % result)
    # Fix result
    if result[1] == '':
        result[1] = '0K'
    if result[2] == '':
        result[2] = '0MS'
    # Get compile info
    html, t = CurlGET("http://poj.org/showcompileinfo?solution_id=%s" % RunID, cookie)
    c_info = re.findall(r'<font size=3>(.*)</font></p></ul>', html, re.S)
    c_info = c_info[0].replace('<pre>', '').replace('</pre>', '').strip()
    if c_info == '&nbsp;':
        c_info = ''
    # Update result
    db.run_sql("update Record set `rid`='%s',`memory`='%s',`long`='%s',`lang`='%s',`result`='%s',`compileinfo`='%s' where `id`='%s'" % (RunID, result[1], result[2], result[3], result[0], MySQLdb.escape_string(c_info), item[0]))
    if result[0] == 'Accepted':
        t_res = db.run_sql("select distinct oid from Record where `user`='%s' and result='Accepted'" % item[4])
        t_res = map(lambda ptr:ptr[0], t_res)
        db.run_sql("update Users set `plist`='%s',`ac`='%d' where `user`='%s'" % (' '.join(t_res), len(t_res), item[4]))
        Log('[I] => Solved problem list updated')
    ExitThread(index, cookie)
    thread.exit_thread()

def Watcher():
    global TaskCount
    while True:
        time.sleep(3)
        try:
            can = db.config.count_thread - TaskCount
            if can == 0:
                continue
            Log('[I] => Have %d idlei thread ...' % can)
            res = getList(can)
            for item in res:
                Mutex.acquire()
                TaskCount += 1
                Mutex.release()
                uu = []
                for i in range(0, len(db.config.poj_user)):
                    if db.config.poj_user[i][2] != True:
                        uu = db.config.poj_user[i]
                        db.config.poj_user[i][2] = True
                        break
                db.run_sql("update Record set result='Waiting' where `id`='%s'" % str(item[0]))
                thread.start_new_thread(Worker, (item, uu[0], uu[1], i))
        except Exception,e:
            print e    

def main():
    Log('[I] => Starting Watcher thread ...')
    thread.start_new_thread(Watcher, ())
    while True:
        time.sleep(1000)

if __name__ == '__main__':
    main()
