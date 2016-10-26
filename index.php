<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-17 17:23:07
        Filename: index.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (!file_exists('.install'))
	{
		header('Location: Install.php');
		die();
	}
	require_once('api.php');
	$db = new MySQL();
	if (isset($_GET['cid']))
	{
		$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
		if (!$res)
		{
			die('<center><h1><a href="index.php" style="color: #000000;">No such contest !</a></h1></center>');
		}
		@session_start();
		if (!empty($res['password']))
		{
			if (!isset($_SESSION['contest_'.intval($_GET['cid'])]))
			{
				header('Location: password.php?cid='.intval($_GET['cid']));
				die();
			} else {
				if ($res['password'] != $_SESSION['contest_'.intval($_GET['cid'])])
				{
					header('Location: password.php?cid='.intval($_GET['cid']));
					die();
				}
			}
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
        	<h1>Problems List</h1>
    	<?php
			$sstart = isset($_GET['page'])?(intval($_GET['page'])-1)*10:0;
			require_once("api.php");
            $ac = array();
            if ($app->user->isLogin())
                $ac = explode(' ', $app->user->getPlist());
			if ($db->query("SHOW TABLES LIKE 'Problem'")->num_rows() != 1)
			{
				$db->struct(array(
						'id' => 'text',
						'pid' => 'text',
						'title' => 'text',
						'oj' => 'text',
						'hide' => 'text'
					))->create("Problem");
			}
			echo "<table border='1'><tr><td width='100'>Problem ID</td><td width='500'>Problem Title</td></tr>";
			if (isset($_GET['cid']))
			{
				$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
				if ($res['time_s'] > time())
					die('<center><h1><a href="index.php" style="color: #000000;">Contest not start !</a></h1></center></body></html>');
				$ll = explode(',', $res['list']);
				for ($i=0;$i<count($ll);++$i)
				{
					$list = $db->from('Problem')->where("`id`='".$ll[$i]."'")->select()->fetch_one();
					echo "<tr><td width='100'>".chr(intval($i) + 65)."</td><td width='500'><a ".(in_array($ll[$i], $ac)?"class='ac'":"")."  href='view.php?cid=".$_GET['cid']."&id=".$list['id']."'>".$list['title']."</a></td></tr>";
				}
			} else {
				$list = $db->from("Problem")->where("`hide`='no'")->limit(10, $sstart)->select()->fetch_all();
				for ($i=0;$i<count($list);++$i)
					echo "<tr><td width='100'>".$list[$i]['id']."</td><td width='500'><a ".(in_array($list[$i]['id'], $ac)?"class='ac'":"")." href='view.php?id=".$list[$i]['id']."'>".$list[$i]['title']."</a></td></tr>";
			}
			echo "</table>";
		?><br /><br />
		<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
		<br /><br />
        </center>
    </body>
</html>
