<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-10 17:19:34
        Filename: password.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	$alert = '';
	if (isset($_GET['cid']) && isset($_POST['password']))
	{
		require_once('api.php');
		$db = new MySQL();
		$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
		if (!$res)
			$alert = 'No such contest !';
		elseif ($res['password'] != $_POST['password'])
			$alert = 'Wrong password !';
		else {
			@session_start();
			$_SESSION['contest_'.intval($_GET['cid'])] = $_POST['password'];
			header('Location: index.php?cid='.intval($_GET['cid']));
			die();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Problem List</title>
    </head>
    <body>
    	<center>
        	<?php require_once("header.php"); ?>
			<?php
				if (!isset($_GET['cid']))
					die('<center><h1><a href="index.php" style="color: #000000;">No such contest or permission denied !</a></h1></center></body></html>');
			?>
        	<h1>Confirm Password</h1>
			<h3 style='color: red;'><?php echo $alert; ?></h3>
			<form action='password.php?cid=<?php echo intval($_GET['cid']); ?>' method='post'>
				<input type='password' name='password' />&nbsp;<input type='submit' name='submit' value='Check' />
			</form>
        </center>
    </body>
</html>
