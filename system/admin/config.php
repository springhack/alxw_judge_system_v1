<?php
	require_once("../App.class.php");
	App::loadMod("User");
	App::loadMod("Eassy");
	App::loadMod("Tools");
	App::loadMod("Setting");
	$app = new App();
	$user = new User();
	$tools = new Tools();
	$setting = new Setting();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	if ($user->getPower() != 0)
		header("Location: status.php?action=login");
	$alert = "";
	if (isset($_POST['submit']))
	{
		$setting->set("SiteName", $tools->dealString($_POST['SiteName']));
		$setting->set("SiteDescription", $tools->dealString($_POST['SiteDescription']));
		$setting->set("SiteURL", $tools->dealString($_POST['SiteURL']));
		$setting->set("SiteCache", $tools->dealString($_POST['SiteCache']));
		$setting->set("SiteEmail", $tools->dealString($_POST['SiteEmail']));
		$alert = "保存成功!";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>网站属性</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
        <style>
			input[type='text'] {
				width: 300px;
			}
		</style>
    </head>
    <body>
    	<div id="main">
        	<form action="config.php" method="post">
            	<h1 style="color: #F00;"><?php echo $alert; ?></h1>
            	<label>网站标题: </label><input value="<?php echo $setting->get("SiteName"); ?>" type="text" name="SiteName" /> (SiteName)<br /><br />
                <label>网站描述: </label><input value="<?php echo $setting->get("SiteDescription"); ?>" type="text" name="SiteDescription" /> (SiteDescription)<br /><br />
                <label>网站地址: </label><input value="<?php echo $setting->get("SiteURL"); ?>" type="text" name="SiteURL" /> (SiteURL)<br /><br />
                <label>缓存地址: </label><input value="<?php echo $setting->get("SiteCache"); ?>" type="text" name="SiteCache" /> (SiteCache)<br /><br />
                <label>网站邮箱: </label><input value="<?php echo $setting->get("SiteEmail"); ?>" type="text" name="SiteEmail" /> (SiteEmail)<br /><br />
                <input type="submit" name="submit" value="保存网站属性" /><br /><br />
            </form>
        </div>
    </body>
</html>