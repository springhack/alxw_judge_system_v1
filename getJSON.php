<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-13 20:47:50
        Filename: getJSON.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (!isset($_GET['id']))
		die(json_encode(array('Error')));
  	require_once('api.php');
	require_once('classes/Record.php');
	if (!preg_match('/^\w*$/', $_GET['id']))
		die(json_encode(array('Error')));
	$db = new MySQL();
	$arr = $db->from('Record')->where('`id`=\''.$_GET['id'].'\'')->select('id')->fetch_one();
	if (!isset($arr['id']))
		die(json_encode(array('Error')));
	$pro = new Record($arr['id']);
	$res = $pro->getInfo();
	$is_contest = false;
	if ($_GET['cid'] != '0')
	{
		$contest_fix = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select('list')->fetch_one();
		if ($contest_fix)
		{
			$is_contest = true;
			$tmp_arr = explode(',', $contest_fix['list']);
			$hash = array();
			for ($i=0;$i<count($tmp_arr);++$i)
				$hash[$tmp_arr[$i]] = chr(65 + $i);
		}
	}
	if ($is_contest)
		$res['oid'] = $hash[$res['oid']];
	echo json_encode(array(
		$res['id'],
		$res['user'],
		$res['oid'],
		$res['result'],
		$res['memory'],
		$res['long'],
		$res['lang'],
		date("Y-M-D H:i:s", $res['time']),
        'code' => $res['code'],
        'compileinfo' => $res['compileinfo']
	));
?>
