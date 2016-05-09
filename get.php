<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-09 21:45:26
        Filename: get.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
 	require_once("api.php");
	require_once("classes/Problem.php");
	preg_match('/[^c]id=(\d*)/', $_SERVER["HTTP_REFERER"], $match);
	$id = $match[1];
	$id = is_numeric($id)?$id:0;
	$db = new MySQL();
	$info = $db->from("Problem")->where("`id` = '".$id."'")->select()->fetch_one();
	$prefix = "";
	require_once('Config.Daemon.php');
	if (isset($conf['OJ_PREFIX_LIST'][$info['oj']]))
		$prefix = $conf['OJ_PREFIX_LIST'][$info['oj']];
	$rep = '';
	for ($i=0;$i<min(strlen($_SERVER['SCRIPT_NAME']), strlen($_SERVER['REQUEST_URI']));++$i)
		if ($_SERVER['SCRIPT_NAME'][$i] == $_SERVER['REQUEST_URI'][$i])
			$rep .= $_SERVER['SCRIPT_NAME'][$i];
	$path = str_replace($rep, '', $_SERVER['REQUEST_URI']);
	echo file_get_contents($prefix.$path);
?>
