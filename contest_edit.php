<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-10 17:54:28
        Filename: contest_edit.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("api.php");
	if (!$app->user->isLogin())
		die('<center><a href=\'admin/status.php?action=login&url=../index.php\'>Please login or register first!</a></center>');
	if ($app->user->getPower() != 0)
		die('<center><a href=\'admin/status.php?action=login&url=../index.php\'>Please login or register first!</a></center>');
	if (isset($_POST['submit']))
	{
		$db = new MySQL();
		if ($db->from('Contest')->where('`id`=\''.$_POST['id'].'\'')->select()->num_rows() == 1)
		{
			unset($_POST['submit']);
			$_POST['time_s'] = strtotime($_POST['time_s']);
			$_POST['time_e'] = strtotime($_POST['time_e']);
			$db->set($_POST)
				->where("`id`='".$_POST['id']."'")
				->update('Contest');
		} else {
			if (!empty($_POST['title']))
			{	
				$num = $db->from("Contest")
						->select("max(cast(id as signed))")
						->fetch_one();
				//Just a hack for PHP <= 5.3
				$db->value(array(
					'id' => intval($num['max(cast(id as signed))']) + 1,
					'title' => get_magic_quotes_gpc()?$_POST['title']:addslashes($_POST['title']),
					'list' => get_magic_quotes_gpc()?$_POST['list']:addslashes($_POST['list']),
					'password' => get_magic_quotes_gpc()?$_POST['password']:addslashes($_POST['password']),
					'time_s' => strtotime($_POST['time_s']),
					'time_e' => strtotime($_POST['time_e']),
					'rank' => 'a:0:{}',
					'time' => time()
					))->insert("Contest");
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>比赛管理</title>
        <link rel="stylesheet" href="admin/css/frame.css" type="text/css" />
    </head>
    <body>
		<center>
        	<br />
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>添加/编辑比赛</td>
				</tr>
				<tr>
					<td align='center' style='padding: 10px;'>
						<form action='contest_edit.php' method='post'>
							比赛ID:<input type='text' id='id' name='id' readonly /><br /><br />
							比赛标题:<input type='text' id='title' name='title' /><br /><br />
							比赛题目:<input type='text' id='list' name='list' /><br /><br />
							比赛密码:<input type='text' id='password' name='password' /><br /><br />
							开始时间:<input type='text' id='time_s' name='time_s' value='<?php echo date('Y-m-d H:i:s', time()); ?>' /><br /><br />
							结束时间:<input type='text' id='time_e' name='time_e' value='<?php echo date('Y-m-d H:i:s', time()); ?>' /><br /><br />
							<input type='submit' name='submit' value='保存' />
						</form>
					</td>
				</tr>
			</table><br />
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" style="width: 100px;padding: 3px;">
						ID
					</td>
					<td align="center" style="width: 100px;padding: 3px;">
						Title
					</td>
					<td align="center" style="width: 200px;padding: 3px;">
						Start Time
					</td>
					<td align="center" style="width: 200px;padding: 3px;">
						End Time
					</td>
					<td align="center" style="width: 100px;padding: 3px;">
						Operation
					</td>
				</tr>
				<script>
					var p_list = [];
					function edit(id)
					{
						var ll = ['title', 'password', 'list', 'time_s', 'time_e'];
						document.getElementById('id').value = id;
						for (var i=0;i<ll.length;++i)
						{console.log(ll[i]);
							document.getElementById(ll[i]).value = p_list[id][ll[i]];
						}
					}
				</script>
				<?php
					$db = new MySQL();
					$sstart = isset($_GET['page'])?(intval($_GET['page'])-1)*10:0;
					$list = $db->from("Contest")->limit(10, $sstart)->order('desc', 'time')->select()->fetch_all();
					for ($i=0;$i<count($list);++$i)
					{
						echo "
							<script>
								p_list[".$list[$i]['id']."] = {
									'title' : '".$list[$i]['title']."',
									'password' : '".$list[$i]['password']."',
									'list' : '".$list[$i]['list']."',
									'time_s' : '".date('Y-m-d H:i:s', $list[$i]['time_s'])."',
									'time_e' : '".date('Y-m-d H:i:s', $list[$i]['time_e'])."'
								};
							</script>
							";
						echo "<tr>";
						echo "<td align='center' >".$list[$i]['id']."</td>";
						echo "<td align='center' >".$list[$i]['title']."</td>";
						echo "<td align='center' >".date('Y-m-d H:i:s', $list[$i]['time_s'])."</td>";
						echo "<td align='center' >".date('Y-m-d H:i:s', $list[$i]['time_e'])."</td>";
						echo "<td align='center' ><a href='javascript:edit(".$list[$i]['id'].");'>Edit</a></td>";
						echo "</tr>";
					}
				?>
			</table>
			<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
        </center>
    </body>
</html>
