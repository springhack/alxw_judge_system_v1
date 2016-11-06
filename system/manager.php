<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-11 21:21:11
        Filename: manager.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("api.php");
	if (!$app->user->isLogin())
		die('<center><a href=\'admin/status.php?action=login&url=../index.php\'>Please login or register first!</a></center>');
	if ($app->user->getPower() != 0)
		die('<center><a href=\'admin/status.php?action=login&url=../index.php\'>Please login or register first!</a></center>');
	if (isset($_GET['action']))
	{
		if ($_GET['action'] == "delete")
		{
			$db = new MySQL();
			$db->from("Problem")->where("`id`='".intval($_GET['id'])."'")->delete();
			$db->from("Record")->where("`oid`='".intval($_GET['id'])."'")->delete();
			$db->from("Contest")->where("list like '".intval($_GET['id']).",%' or list like '%,".intval($_GET['id'])."' or list like '%,".intval($_GET['id']).",%' or list='".intval($_GET['id'])."'")->delete();
		}
		if ($_GET['action'] == "trigger")
		{
			$db = new MySQL();
			$hide = $db->from("Problem")->where("`id`='".intval($_GET['id'])."'")->select('hide')->fetch_one();
			if ($hide['hide'] == 'yes')
			{
				$db->set(array('hide' => 'no'))->where("`id`='".intval($_GET['id'])."'")->update('Problem');
			} else {
				$db->set(array('hide' => 'yes'))->where("`id`='".intval($_GET['id'])."'")->update('Problem');
			}
			//Just jump back to referer page
			header('Location: '.$_SERVER['HTTP_REFERER']);
			die();
		}
	}
	if (isset($_POST['submit']))
	{
		require_once("classes/Problem.php");
		$pro = new Problem($_POST['pid'], $_POST['oj']);
		$pro_info = $pro->getInfo();
		$db = new MySQL();
		$db->value(array(
				'pid' => $_POST['pid'],
				'title' => get_magic_quotes_gpc()?$pro_info['title']:addslashes($pro_info['title']),
				'oj' => $_POST['oj'],
				'hide' => 'no'
			))
			->insert("Problem");
		$alert = "Problem ".$pro_info['title']." added !";
	}
	if (isset($_POST['clean']))
	{
		$db = new MySQL();
		$db->from("Problem")->delete();
		$db->from("Record")->delete();
		$db->from("Contest")->delete();
		$app->setting->set("lastArray", "a:0:{}");
		$app->setting->set("lastCache", time());
		$app->setting->set("startTime", time());
		$app->setting->set("endTime", time());
		$alert = "我都忘了耶~!";
	}
	if (isset($_POST['rejudge']))
	{
        $w = '';
        if (isset($_POST['pid']) && is_numeric($_POST['pid']))
            $w .= "`oid`='".$_POST['pid']."'";
        if (isset($_POST['cid']) && is_numeric($_POST['cid']))
            if ($w != '')
                $w .= " and `contest`='".$_POST['cid']."'";
            else
                $w .= "`contest`='".$_POST['cid']."'";
        if ($w == '')
		    $alert = "没有筛选条件，啥也没干咯～!";
        else {
    		$db = new MySQL();
            $db->set(array(
                'rid' => '__',
                'result' => 'Rejudge'
            ))->where($w)->update('Record');
		    $alert = "嗯，已经开始Rejudge了～!";
        }
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>题目管理</title>
        <link rel="stylesheet" href="admin/css/frame.css" type="text/css" />
    </head>
    <body>
		<center>
        	<br /><br />
            <?php
            	if (isset($alert))
					echo "<h1>".$alert."</h1><br />";
			?>
			<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td align="center" style="padding: 10px;">
                    	<h2>Add Problem</h2>
                    </td>
                    <td align="center" style="padding: 10px;">
                    	<h2>Rejudge</h2>
                    </td>
                    <td align="center" style="padding: 10px;">
                    	<h2 style='color:#f00;'>Clean System</h2>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 10px;">
                    	<form action="manager.php" method="post"><br /><br />
                            <label>Problem ID:&nbsp;</label><input type="text" name="pid" /><br /><br />
                            <label>Problem OJ:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <select name="oj">
								<?php
									require_once('../Config.Daemon.php');
									for ($i=0;$i<count($conf['OJ_LIST']);++$i)
										echo '<option value="'.$conf['OJ_LIST'][$i].'">'.$conf['OJ_LIST'][$i].'</option>';
								?>
                            </select><br /><br />
                            <input type="submit" value="Submit" name="submit" />
                        </form>
                    </td>
                    <td align="center" style="padding: 10px;">
                    	<form action="manager.php" method="post">
                            Problem: <input type='text' name='pid' /><br /><br />
                            Contest: <input type='text' name='cid' /><br /><br />
                        	<input type="submit" name="rejudge" value="重新来过" />
                        </form>
                    </td>
                    <td align="center" style="padding: 10px;">
                    	<form action="manager.php" method="post">
                            <font style='color:#f00;'>药效强烈，提神醒脑!</font>
                            <br />
                        	<input type="submit" name="clean" value="一切皆忘,初始化系统" style='background-color:#f00;color:#fff;' />
                            <br />
                            <font style='color:#f00;'>副作用强，建议慎用!</font>
                        </form>
                    </td>
                </tr>
            </table><br /><br />
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" style="width: 50px;">
						ID
					</td>
					<td align="center" style="min-width: 200px;">
						Title
					</td>
					<td align="center" style="width: 100px;">
						OJ
					</td>
					<td align="center" style="width: 100px;">
						Remote ID
					</td>
					<td align="center" style="width: 200px;">
						Operation
					</td>
				</tr>
				<?php
					$db = new MySQL();
					$sstart = isset($_GET['page'])?(intval($_GET['page'])-1)*10:0;
					$list = $db->from("Problem")->limit(10, $sstart)->order('desc', 'cast(id as signed)')->select()->fetch_all();
					for ($i=0;$i<count($list);++$i)
					{
						echo "<tr>";
						echo "<td align='center' style='padding: 10px;'>".$list[$i]['id']."</td>";
						echo "<td align='center' style='padding: 10px;'>".$list[$i]['title']."</td>";
						echo "<td align='center' style='padding: 10px;'>".$list[$i]['oj']."</td>";
						echo "<td align='center' style='padding: 10px;'>".$list[$i]['pid']."</td>";
						echo "<td align='center' style='padding: 10px;'>
							<a href='manager.php?action=delete&id=".$list[$i]['id']."'>Delete</a>
							&nbsp;|&nbsp;
							<a href='manager.php?action=trigger&id=".$list[$i]['id']."'>".(($list[$i]['hide'] == 'no')?'Hide':'<font style="color: red;">Show</font>')."</a>
							</td>";
						echo "</tr>";
					}
				?>
			</table>
			<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
        </center>
    </body>
</html>
