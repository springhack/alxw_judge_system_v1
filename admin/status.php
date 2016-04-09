<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-31 10:07:31
        Filename: status.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (isset($_GET['url']))
		$url = $_GET['url'];
	else
		$url = "index.php";
	if (!isset($_GET['action']))
		header("Location: ".$url);
	require_once("../App.class.php");
	App::loadMod("User");
	App::loadMod("Setting");
	$app = new App();
	$user = new User();
	$setting = new Setting();
	$alert = "";
	$return = false;
	switch ($_GET['action'])
	{
		case "register":
			if ($setting->get("RegOpen", "on") == "off")
			{
				$alert = "注册失败,不开放注册,3秒后返回!";
				$return = true;
			} else {
				if (isset($_POST['submit']))
				{
					if (!$user->user_pass_check($_POST['user'], $_POST['pass']))
					{
						$alert = "注册失败，账号密码不符合要求";
						break;
					}
					if ($user->userRegister($_POST['user'], $_POST['pass'], ""))
					{
						$alert = "注册成功,3秒后返回!";
						$return = true;
					} else
						$alert = "注册失败!";
				}
			}
		break;
		case "logout":
			$alert = "退出成功,3秒后返回!";
			$return = true;
			$user->userLogout();
		break;
		default:
		case "login":
			if (isset($_POST['submit']))
			{
				if ($user->userLogin($_POST['user'], $_POST['pass']))
				{
					$alert = "登录成功,3秒后返回!";
					$return = true;
				} else
					$alert = "登录失败!";
			}
		break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>后台管理登录</title>
        <link rel="stylesheet" href="css/login.css" type="text/css" />
        <script language="javascript" src="../Widget/jQuery/jquery-2.1.3.min.js"></script>
        <script language="javascript" src="js/login.js"></script>
    </head>
    <body>
    	<center>
        	<div id="title">
            	<h2>后台管理V2登陆</h2>
                <h5>Powered by Cello Studio</h5>
            </div>
            <div id="login">
            	<div id="up">
                	<h3 style="color: #F00;" id="alert"><?php echo $alert; ?></h3>
                	登录<hr /><br />
                    <form action="status.php?action=login&url=<?php echo $url; ?>" method="post">
                    账号:&nbsp;<input type="text" name="user" /><br /><br />
                    密码:&nbsp;<input type="password" name="pass" /><br /><br />
                    <div style="text-align: right;">
                    	<input type="submit" name="submit" value="登录" />
                    </div><hr />
                    </form>
                </div>
                <div id="down">
                	<a href=".." style="float: left;">←返回首页</a><a href="javascript:register();" style="float: right;">注册账户→</a>
                </div>
            </div>
            <div id="register" style="display: none;">
            	<div id="up">
                	<h3 style="color: #0F0;" id="prompt"></h3>
                	注册<hr /><br />
                    <form action="status.php?action=register&url=<?php echo $url; ?>" method="post">
                    账号:&nbsp;<input type="text" name="user" /><br /><br />
                    密码:&nbsp;<input type="password" name="pass" id="pass" /><br /><br />
                    重复:&nbsp;<input type="password" name="check" id="check" onkeyup="javascript:deal();" /><br /><br />
                    <div style="text-align: right;">
                    	<input type="submit" name="submit" value="注册" />
                    </div><hr />
                    </form>
                </div>
                <div id="down">
                	<a href="javascript:login();">←返回登陆</a>
                </div>
            </div>
        </center>
    </body>
    <script language="javascript">
		<?php
			if ($return) {
			?>
				setTimeout(function () {
						location.href = "<?php echo $url; ?>";
					}, 3000);
			<?php
			}
		?>
	</script>
</html>
