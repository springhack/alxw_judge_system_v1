<?php
	require_once("../App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	$alert = "";
	if (isset($_POST['old']) && isset($_POST['new']))
	{
		if ($_POST['new'] != "")
			if ($user->str_check($_POST['new']) && $user->getSalt($_POST['old']) == $user->getPass())
			{
				$flag = $user->userRenew($user->getUser(), $user->getSalt($_POST['new']), "", $user->getPower());
				if ($flag)
					$alert = "修改成功!";
				else
					$alert = "修改失败!";
			} else
				$alert = "修改失败!";
	}
    $plist = $user->getPlist();
    $list = explode(' ', $plist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>用户管理 > <?php echo $user->getUser(); ?>的资料</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
    </head>
    <body>
    	<center>
       		<form action="profile.php" method="post">
            	<h1 style="color: #F00;"><?php echo $alert; ?></h1>
                <div id="main">
                    <label>账号: </label><?php echo $user->getUser(); ?><br /><br />
                    <label>权限: </label><?php echo ($user->getPower() == 0)?"管理员":"普通用户"; ?><br /><br />
                    <label>密码修改:</label><br /><br />
                    <label> 原密码(<font style="color: #F00;">*</font>): </label><input type="password" name="old" /><br /><br />
                    <label> 新密码(<font style="color: #F00;">*</font>): </label><input type="password" name="new" /> (<font style="color: #0F0;">不修改请留空</font>)<br /><br />
                    <input type="submit" name="submit" value="提交修改" /><br /><br />
                    <label>通过的题目:</label><br /><br />
                    <p>
                        <?php
                            foreach ($list as $p)
                                echo '<a href="#" onclick="javascript:window.parent.document.location.href=\'../view.php?id='.$p.'\'">'.$p.'</a>&nbsp;';
                        ?>
                    </p>
                </div>
            </form>
        </center>
    </body>
</html>
