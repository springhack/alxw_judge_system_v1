<?php
	require_once("../App.class.php");
	App::loadMod("User");
	App::loadMod("Setting");
	App::loadMod("Tools");
	$app = new App();
	$user = new User();
	$setting = new Setting();
	$tools = new Tools();
	if (!$user->isLogin())
		redirect("Location: status.php?action=login");
	if ($user->getPower() != 0)
		redirect("Location: status.php?action=login");
	$alert = "";
	if (isset($_POST['submit']) && isset($_POST['type']))
	{
		$arr = explode(",", $_POST['type']);
		if ($setting->set("EassyType", $tools->dealString(serialize($arr))))
			$alert = "修改成功!";
		else
			$alert = "修改失败!";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>文章管理 > 文章分类</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
    </head>
    <body>
    	<div id="main">
        	<form action="etype.php" method="post">
            	<h1 style="color: #F00;"><?php echo $alert; ?><font style="color: #0F0;">请使用英文逗号(,)分割每个类别哦~~~</font></h1>
				<?php
                    $list = unserialize($setting->get("EassyType"));
					if (!is_array($list))
						$list = array();
                    $str = implode(",", $list);
					echo '<input type="text" name="type" value="'.$str.'" /><br /><br />';
                ?>
                <input type="submit" name="submit" value="提交修改" /><br /><br />
            </form>
        </div>
    </body>
</html>