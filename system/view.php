<?php
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
        <title>View Problem</title>
    </head>
    <body>
    	<center>
        <?php require_once("header.php"); ?>
		<?php
			if (isset($_GET['cid']))
			{
				$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
				if ($res['time_s'] > time())
					die('<center><h1><a href="index.php" style="color: #000000;">Contest not start !</a></h1></center></body></html>');
				$list = explode(',', $res['list']);
				if (!in_array(intval($_GET['id']), $list))
					die('<center><h1><a href="index.php" style="color: #000000;">No such problem or permission denied !</a></h1></center></body></html>');
			} else {
				$res = $db->from('Problem')->where("`id`='".intval($_GET['id'])."'")->select('hide')->fetch_one();
				if ($res['hide'] == 'yes')
					die('<center><h1><a href="index.php" style="color: #000000;">No such problem or permission denied !</a></h1></center></body></html>');
			}
            function dealTitle($str)
            {
                return ucwords(str_replace('_', ' ', $str));
            }
		?>
        <h1>View Problem</h1>
        <table border="1">
        	<tr>
            	<td width="200">
            		<h2>Submit Code</h2>
            	</td>
                <td width="600">
                	<a href="submit.php?<?php
						if (isset($_GET['cid']))
							echo 'cid='.$_GET['cid'].'&';
					?>id=<?php echo $_GET['id']; ?>">Submit</a>
                </td>
            </tr>
            <?php
                require_once("classes/Problem.php");
                $info = $db->from("Problem")->where("`id` = '".$_GET['id']."'")->select()->fetch_one();
                $pro = new Problem($info['pid'], $info['oj'], isset($_GET['cid'])?$_GET['cid']:'0');
                $pro_info = $pro->getInfo();
                foreach ($pro_info as $key => $val)
                {
                    echo "<tr><td width='200'><h2>".dealTitle($key)."</h2></td><td width='800'>";
                    if (strstr($key, "sample_"))
                        echo "<pre>".$val."</pre></td></tr>";
                    else
                        echo $val."</td></tr>";
                }
            ?>
            </table>
            <br /><br />
        </center>
    </body>
</html>
