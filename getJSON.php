<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-04 18:39:48
        Filename: getJSON.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (!isset($_GET['id']))
		die(json_encode(array('Error')));
  	require_once('api.php');
	require_once('classes/Record.php');
	if (!preg_match('/[\da-zA-Z]*/', $_GET['id']))
		die(json_encode(array('Error')));
	$db = new MySQL();
	$arr = $db->from('Record')->where('`id`=\''.$_GET['id'].'\'')->select('id')->fetch_one();
	if (!isset($arr['id']))
		die(json_encode(array('Error')));
	$pro = new Record($arr['id']);
	$res = $pro->getInfo();
	echo json_encode(array(
		$res['id'],
		$res['user'],
		$res['oid'],
		$res['result'],
		$res['memory'],
		$res['long'],
		$res['lang'],
		date("Y-M-D H:i:s", $res['time'])
	));
?>
