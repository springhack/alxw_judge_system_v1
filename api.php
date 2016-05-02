<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-02 23:10:05
        Filename: ../../../vj/api.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	date_default_timezone_set("PRC");
	require_once(dirname(__FILE__)."/App.class.php");
	$app = new App();
	$app->page = new Page();
	$app->user = new User();
	$app->eassy = new Eassy();
	$app->tools = new Tools();
	$app->setting = new Setting();
	$app->tools->dealSiteOpen();
	if (isset($_GET['cid']))
		$_GET['cid'] = intval($_GET['cid']);
?>
