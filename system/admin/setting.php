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
		$setting->set("SiteOpen", $tools->dealString($_POST['SiteOpen']));
		$setting->set("RegOpen", $tools->dealString($_POST['RegOpen']));
		$setting->set("TalkOpen", $tools->dealString($_POST['TalkOpen']));
		$setting->set("UploadOpen", $tools->dealString($_POST['UploadOpen']));
		$setting->set("CloseReason", $tools->dealString($_POST['CloseReason']));
		$alert = "保存成功!";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>网站设置</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
        <script language="javascript" type="text/javascript" src="../Widget/codeEditor/edit_area_full.js"></script>
        <script language="javascript">
			editAreaLoader.init({
				id: "CloseReason"	// id of the textarea to transform		
				,start_highlight: true	// if start with highlight
				,allow_resize: "both"
				,allow_toggle: true
				,word_wrap: true
				,language: "en"
				,syntax: "html"	
			});
		</script>
        <style>
			input[type='text'] {
				width: 300px;
			}
		</style>
    </head>
    <body>
    	<div id="main">
        	<form action="setting.php" method="post">
            	<h1 style="color: #F00;"><?php echo $alert; ?></h1>
            	<label>网站开关: </label>
                	<select name="SiteOpen">
                    	<?php if ($setting->get("SiteOpen", "on") == "on") { ?>
                            <option value="on" selected="selected">开启</option>
                            <option value="off">关闭</option>
                        <?php } else { ?>
                        	<option value="on">开启</option>
                            <option value="off" selected="selected">关闭</option>
                        <?php } ?>
                    </select> (SiteOpen)<br /><br />
                <label>注册开关: </label>
                	<select name="RegOpen">
                    	<?php if ($setting->get("RegOpen", "on") == "on") { ?>
                            <option value="on" selected="selected">开启</option>
                            <option value="off">关闭</option>
                        <?php } else { ?>
                        	<option value="on">开启</option>
                            <option value="off" selected="selected">关闭</option>
                        <?php } ?>
                    </select> (RegOpen)<br /><br />
                <label>评论开关: </label>
                	<select name="TalkOpen">
                    	<?php if ($setting->get("TalkOpen", "on") == "on") { ?>
                            <option value="on" selected="selected">开启</option>
                            <option value="off">关闭</option>
                        <?php } else { ?>
                        	<option value="on">开启</option>
                            <option value="off" selected="selected">关闭</option>
                        <?php } ?>
                    </select> (TalkOpen)<br /><br />
                <label>上传开关: </label>
                	<select name="UploadOpen">
                    	<?php if ($setting->get("UploadOpen", "on") == "on") { ?>
                            <option value="on" selected="selected">开启</option>
                            <option value="off">关闭</option>
                        <?php } else { ?>
                        	<option value="on">开启</option>
                            <option value="off" selected="selected">关闭</option>
                        <?php } ?>
                    </select> (UploadOpen)<br /><br />
                <label>关站原因(CloseReason,网站关闭时生效,支持HTML):</label><br /><br />
                <textarea id="CloseReason" name="CloseReason" rows="13" cols="80"><?php echo $setting->get("CloseReason", ""); ?></textarea><br />
                <input type="submit" name="submit" value="保存网站设置" /><br /><br />
            </form>
        </div>
    </body>
</html>