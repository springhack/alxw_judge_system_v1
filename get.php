<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-12-08 10:29:25
        Filename: get.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
 	require_once("api.php");
	require_once("classes/Problem.php");
	$id = explode("id=", $_SERVER["HTTP_REFERER"]);
	$id = isset($id[1])?$id[1]:0;
	$db = new MySQL();
	$info = $db->from("Problem")->where("`id` = '".$id."'")->select()->fetch_one();
	$prefix = "";
	switch ($info['oj'])
	{
		case "POJ":
			$prefix = "http://poj.org";
		break;
		case "HDOJ":
			$prefix = "http://acm.hdu.edu.cn";
		break;
		default:
		break;
	}
	echo file_get_contents($prefix.$_SERVER["REQUEST_URI"]);
?>
