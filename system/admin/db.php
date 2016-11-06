<?php
	require_once("../App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	if ($user->getPower() != 0)
		header("Location: status.php?action=login");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>MySQL数据库登陆中...</title>
        <script language="javascript" src="../Widget/jQuery/jquery-2.1.3.min.js"></script>
    </head>
    <body>
    	<script language="javascript">
			var server = "<?php echo $Config['DB_HOST']; ?>";
			var user = "<?php echo $Config['DB_USER']; ?>";
			var pass = "<?php echo $Config['DB_PASS']; ?>";
			var db = "<?php echo $Config['DB_NAME']; ?>";
			$(function () {
					$.post("adminer.php", {
							'auth[driver]' : 'server',
							'auth[server]' : server,
							'auth[username]' : user,
							'auth[password]' : pass,
							'auth[db]' : db
						}, function () {
								location.href = 'adminer.php?server=' + server + '&username=' + user + '&db=' + db;
							});
				});
		</script>
    </body>
</html>
