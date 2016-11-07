<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-10 16:44:57
        Filename: Install.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once(dirname(__FILE__)."/App.class.php");
	if (file_exists(".install"))
		die("<center>You have installed.</center>");
	$app = new App();
	mysql_query("CREATE DATABASE ".$Config['DB_NAME'], $sql);
	mysql_select_db($Config['DB_NAME'], $sql);
	mysql_query("SET NAMES utf8", $sql);
	$app->user = new User();
	$app->user->userRegister($Config['AUTO_USER'], $app->user->getSalt($Config['AUTO_PASS']), 'a:1:{s:5:"quote";s:0:"";}', 0);
	if (!file_put_contents(".install", "Cello Studio"))
		die('Can\'t write file !');
	die("<center>Step 1 finished.<br />
	<a href='index.php' target='_blank'>Click here to step 2</a><br />
	<a href='contest.php' target='_blank'>Click here to step 3</a><br />
	<a href='status.php' target='_blank'>Click here to step 4</a><br />
	<a href='index.php'>Finish it !</a></center>");
?>
