<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-14 09:42:55
        Filename: result.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!--<meta http-equiv="refresh" content="3" />-->
        <title>Status List</title>
		<script language'javascript' src='javascript/progress.js'></script>
    </head>
    <body>
    	<center>
        <?php require_once("header.php"); ?>
        <h1>Problem Status List</h1>
		<?php
			if (!isset($_GET['id']))
				die('<center><h2>非法操作!</h2></center>');
			if (!preg_match("/^\w*$/", $_GET['id']))
				die('<center><h2>非法操作!</h2></center>');
           	require_once('api.php');
			require_once('classes/Record.php');
			$db = new MySQL();
			$arr = $db->from('Record')->where('`id`=\''.$_GET['id'].'\'')->select('id,user')->fetch_all();
            if ($arr[0]['user'] != $app->user->getUser())
                die('<center><h3><a href=\'admin/status.php?action=login&url=../index.php\'>You hane no permission to view this page !</a></h3></center>');
		?>
		<div id='progress'><div id='now'></div></div>
    	<table border="1">
        	<tr>
            	<td>
                	Run ID
                </td>
                <td>
                	User
                </td>
                <td>
                	Problem ID
                </td>
                <td>
                	Result
                </td>
                <td>
                	Memory
                </td>
                <td>
                	Time
                </td>
                <td>
                	Language
                </td>
                <td>
                	Submit Time
                </td>
            </tr>
            <?php
				$is_contest = false;
				if (isset($_GET['cid']))
				{
					$contest_fix = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select('list')->fetch_one();
					if ($contest_fix)
					{
						$is_contest = true;
						$tmp_arr = explode(',', $contest_fix['list']);
						$hash = array();
						for ($i=0;$i<count($tmp_arr);++$i)
							$hash[$tmp_arr[$i]] = chr(65 + $i);
					}
				}
				for ($i=0;$i<count($arr);++$i)
				{
					$pro = new Record($arr[$i]['id']);
					$res = $pro->getInfo();
					?>
            <tr>
                <td>
                	<?php echo $res['id']; ?>
                </td>
                <td>
                	<?php echo $res['user']; ?>
                </td>
                <td>
                	<?php
						if ($is_contest)
							echo $hash[$res['oid']];
						else
							echo $res['oid'];
					?>
                </td>
                <td>
                	<?php echo $res['result']; ?>
                </td>
                <td>
                	<?php echo $res['memory']; ?>
                </td>
                <td>
                	<?php echo $res['long']; ?>
                </td>
                <td>
                	<?php echo $res['lang']; ?>
                </td>
                <td>
                	<?php echo date("Y-M-D H:i:s", $res['time']); ?>
                </td>
            </tr>
                    <?php
				}
			?>
        </table>
        <div id='info' style='display:flex;text-align:left;'>
            <div style='width:50%;'>
                <legend>Code:</legend>
                <pre id='code' style='font-size:11px;white-space:pre-wrap;'><?php
                    echo $res['code'];
                ?></pre>
            </div>
            <div style='width:50%;'>
                <legend>Compile Info:</legend>
                <pre id='compile' style='font-size:11px;white-space:pre-wrap;'><?php
                    echo $res['compileinfo'];
                ?></pre>
            </div>
        </div>
        <br /><br />
		<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
		<script language='javascript'>
			$(function () {
					window.follow_progress(<?php if($is_contest) echo intval($_GET['cid']); ?>);
				});
		</script>
		<br /><br />
        </center>
    </body>
</html>
