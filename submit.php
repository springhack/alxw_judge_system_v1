<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-21 00:35:35
        Filename: ../submit.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("api.php");
	if (!$app->user->isLogin())
		die('<center><a href=\'admin/status.php?action=login&url=../index.php\'>Please login or register first!</a></center>');
	require_once("classes/Problem.php");
	$start = $app->setting->get("startTime", time() + 10);
	if ($start>time())
		die('<center><h1><a href="index.php" style="color: #000000;">Contest not start !</a></h1></center></body></html>');
	$end = $app->setting->get("endTime", time() + 10);
	if ($end<time())
		die('<center><h1><a href="index.php" style="color: #000000;">Contest have finished !</a></h1></center></body></html>');
	$db = new MySQL();
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
		$pro->submitCode($_POST['lang'], $_POST['code']);
		header("Location: result.php?id=".$_SESSION['last_id']);
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
        <form action="submit.php?id=<?php echo $_GET['id']; ?>" method="post" onsubmit="return encodeSource()">
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
