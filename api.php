<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-21 01:05:40
        Filename: ../api.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	date_default_timezone_set("PRC");
	require_once(dirname(__FILE__)."/App.class.php");
	$app = new App();
	$app->page = new Page();
	$app->user = new User();
	$app->talk = new Talk();
	$app->eassy = new Eassy();
	$app->tools = new Tools();
	$app->setting = new Setting();
	$app->tools->dealSiteOpen();
?>
