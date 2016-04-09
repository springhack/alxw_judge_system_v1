<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-09 15:25:50
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
	mysql_query("SET NAMES utf8");
	$app->user = new User();
	$app->user->userRegister($Config['AUTO_USER'], $Config['AUTO_PASS'], "", 0);
	@file_put_contents(".install", "Cello Studio");
	die("<center>Install finished.<br /><a href='index.php'>Goto Index</a></center>");
?>
