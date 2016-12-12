<?php
	require_once("../App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	$alert = "";
	if (isset($_POST['submit']))
	{
		if (isset($_POST['old']) && $user->getSalt($_POST['old']) == $user->getPass())
		{
            $json = $user->getJSON($user->getUser());
            if (isset($_POST['quote']))
                $json['quote'] = htmlspecialchars($_POST['quote']);
            $pass = $user->getPass();
            if (isset($_POST['new']) && !empty($_POST['new']))
                if ($user->str_check($_POST['new']))
                    $pass = $user->getSalt($_POST['new']);
                else
                    $alert = '修改失败!';
            if (isset($_POST['nick']) && $user->nick_check($_POST['nick'], 90))
            {
                $json['nick'] = htmlspecialchars($_POST['nick']);
                $_SESSION['nick'] = $_POST['nick'];
            }
    	    $flag = $user->userRenew($user->getUser(), $pass, serialize($json), $user->getPower());
        	if ($flag && $alert == '')
        	    $alert = "修改成功!";
		} else
			$alert = "修改失败!";
	}
    $plist = $user->getPlist();
    $list = explode(' ', $plist);
    asort($list);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>用户管理 > <?php echo $user->getUser(); ?>的资料</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
        <script language="javascript" type="text/javascript" src="../Widget/codeEditor/edit_area_full.js"></script>
        <script language="javascript">
			editAreaLoader.init({
				id: "quote"	// id of the textarea to transform		
				,start_highlight: true	// if start with highlight
				,allow_resize: "both"
				,allow_toggle: true
				,word_wrap: true
				,language: "en"
				,syntax: "html"	
			});
		</script>
    </head>
    <body>
    	<center>
       		<form action="profile.php" method="post">
            	<h1 style="color: #F00;"><?php echo $alert; ?></h1>
                <div id="main">
                    <label>账号: </label><?php echo $user->getUser(); ?><br /><br />
                    <label>权限: </label><?php echo ($user->getPower() == 0)?"管理员":"普通用户"; ?><br /><br />
                    <label>资料修改:</label><br /><br />
                    <label>昵称: </label><input type='text' name='nick' value='<?php echo $user->getJSON($user->getUser())['nick']; ?>' /><br /><br />
                    <label> 原密码: </label><input type="password" name="old" /> (<font style="color: #F00;">必填</font>)<br /><br />
                    <label> 新密码: </label><input type="password" name="new" /> (<font style="color: #0F0;">不修改请留空</font>)<br /><br />
                    <label> 个性签名: </label><br />
                    <textarea id="quote" name="quote" rows="13" cols="80"><?php echo $user->getJSON($user->getUser())['quote']; ?></textarea><br />
                    <input type="submit" name="submit" value="提交修改" /><br /><br />
                    <label>通过的题目:</label><br />
                    <div style='border: 1px #000 dotted;border-radius: 5px;padding: 5px;'>
                        <?php
                            foreach ($list as $p)
                                echo '<a href="#" onclick="javascript:window.parent.document.location.href=\'../view.php?id='.$p.'\'">'.$p.'</a>&nbsp;';
                        ?>
                    </div>
                </div>
            </form>
        </center>
    </body>
</html>
