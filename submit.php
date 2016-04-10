<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-10 17:14:02
        Filename: submit.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("api.php");
	if (!$app->user->isLogin())
		die('<center><a href=\'admin/status.php?action=login&url=../index.php\'>Please login or register first!</a></center>');
	require_once("classes/Problem.php");
	$db = new MySQL();
	if (isset($_GET['cid']))
	{
		$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
		if (!$res)
		{
			die('<center><h1><a href="index.php" style="color: #000000;">No such contest !</a></h1></center>');
		}
		@session_start();
		if (!empty($res['password']) && $res['password'] != $_SESSION['contest_'.intval($_GET['cid'])])
		{
			header('Location: password.php?cid='.intval($_GET['cid']));
			die();
		}
		$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
		if ($res['time_s'] > time())
			die('<center><h1><a href="index.php" style="color: #000000;">Contest not start !</a></h1></center></body></html>');
		if ($res['time_e'] < time())
			die('<center><h1><a href="index.php" style="color: #000000;">Contest finished !</a></h1></center></body></html>');
		$list = explode(',', $res['list']);
		if (!in_array(intval($_GET['id']), $list))
			die('<center><h1><a href="index.php" style="color: #000000;">No such problem or permission denied !</a></h1></center></body></html>');
	} else {
		$res = $db->from('Problem')->where("`id`='".intval($_GET['id'])."'")->select('hide')->fetch_one();
		if ($res['hide'] == 'yes')
			die('<center><h1><a href="index.php" style="color: #000000;">No such problem or permission denied !</a></h1></center></body></html>');
	}
	$info = $db->from("Problem")->where("`id` = '".$_GET['id']."'")->select()->fetch_one();
	$pro = new Problem($info['pid'], $info['oj']);
	if (isset($_POST['lang']) && isset($_POST['code']))
	{
		if (isset($_SESSION['lasttime']))
		{
			if (time() - intval($_SESSION['lasttime']) < 10)
				die('<center><a href=\'index.php\'>Please submit 10s later !</a></center>');
		}
		$_SESSION['lasttime'] = time();
		$pro->submitCode($_POST['lang'], $_POST['code'], isset($_GET['cid'])?$_GET['cid']:'0');
		header("Location: result.php?id=".$_SESSION['last_id'].(isset($_GET['cid'])?'&cid='.$_GET['cid']:''));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Submit Code</title>
    </head>
    <body>
    	<script language="javascript">
			<?php echo $pro->getEncodeScript(); ?>
		</script>
        <script language="javascript" type="text/javascript" src="Widget/codeEditor/edit_area_full.js"></script>
        <script language="javascript">
			editAreaLoader.init({
				id: "code"	// id of the textarea to transform		
				,start_highlight: true	// if start with highlight
				,allow_resize: "both"
				,allow_toggle: true
				,word_wrap: true
				,language: "en"
				,syntax: "html"	
			});
		</script>
        <center>
        <?php require_once("header.php"); ?>
        <h1>Submit Code</h1>
        <form action="submit.php?<?php
			if (isset($_GET['cid']))
				echo 'cid='.$_GET['cid'].'&';
		?>id=<?php echo $_GET['id']; ?>" method="post" onsubmit="return encodeSource()">
        <table border="1">
        	<tr>
        		<td>
    				<h2>Problem ID: <?php echo $_GET['id']; ?></h2>
                        Language:
                        <select name="lang">
                            <?php
                            	echo file_get_contents(dirname(__FILE__)."/language/".$info['oj'].".txt");
							?>
                        </select>&nbsp;&nbsp;<input name="submit" type="submit" value="Submit" /><br />Code:<br />
            	</td>
            </tr>
            <tr>
            	<td>
                        <textarea name="code" rows="26" cols="110" id="code"></textarea>
                        <input type="hidden" name="rid" id="rid" value="" />
                </td>
            </tr>
        </table>
        </form>
        <br /><br />
        </center>
    </body>
</html>
