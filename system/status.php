<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-16 00:10:33
        Filename: ../../vj/status.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
    require_once('api.php');
	$db = new MySQL();
	if ($db->query("SHOW TABLES LIKE 'Record'")->num_rows() != 1)
	{
		$db->struct(array(
				'id' => 'int primary key not null auto_increment',
				'oid' => 'text',
				'rid' => 'text',
				'tid' => 'text',
				'user' => 'text',
				'time' => 'text',
				'memory' => 'text',
				'long' => 'text',
				'lang' => 'text',
				'result' => 'text',
				'oj' => 'text',
				'oj_u' => 'text',
				'oj_p' => 'text',
				'code' => 'longtext',
				'contest' => 'int',
                'compileinfo' => 'longtext'
			))->create("Record");
        $db->query('ALTER TABLE `Record` ADD INDEX(oid(20)),ADD INDEX(rid(20)),ADD INDEX(tid(20)),ADD INDEX(user(20)),ADD INDEX(result(32)),ADD INDEX(oj(11)),ADD INDEX(contest)');
	}
	if (isset($_GET['cid']))
	{
		$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
		if (!$res)
			die('<center><h1><a href="index.php" style="color: #000000;">No such contest !</a></h1></center>');
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
        <title>Status List</title>
    </head>
    <body>
    	<center>
        <?php require_once("header.php"); ?>
        <h1>Problem Status List</h1>
        <div id='search'>
            <form method='get'>
                <div class='item'>
                    <input type='text' name='oid' /><label>Problem ID</label>
                </div>
                <div class='item'>
                    <input type='text' name='user' /><label>User ID</label>
                </div>
                <select name='result'>
                    <option value='-1'>All</option>
                    <option value='9'>Waiting</option>
                    <option value='0'>Accepted</option>
                    <option value='8'>System Error</option>
                    <option value='7'>Compile Error</option>
                    <option value='5'>Runtime Error</option>
                    <option value='4'>Wrong Answer</option>
                    <option value='1'>Presentation Error</option>
                    <option value='2'>Time Limit Exceeded</option>
                    <option value='6'>Output Limit Exceeded</option>
                    <option value='3'>Memory Limit Exceeded</option>
                </select>
                <?php
                    if (isset($_GET['cid']) && is_numeric($_GET['cid']))
                        echo '<input type=hidden name=cid value='.$_GET['cid'].' />';
                ?>
                <input type='submit' value='Search' />
            </form>
        </div>
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
				$start = isset($_GET['page'])?(intval($_GET['page'])-1)*$Config['STATUS_NUMBER_PER_PAGE']:0;
				require_once('classes/Record.php');
				$is_contest = false;
                $rest_search = '';
                $rest_result = array(
                    'Accepted',
                    'Presentation Error',
                    'Time Limit Exceeded',
                    'Memory Limit Exceeded',
                    'Wrong Answer',
                    'Runtime Error',
                    'Output Limit Exceeded',
                    'Compile Error',
                    'System Error',
                    'Waiting'
                );
                if (isset($_GET['oid']))
                    if(!empty($_GET['oid']) && is_numeric($_GET['oid']))
                        $rest_search .= " and `oid`='".intval($_GET['oid'])."'";
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
                        if (isset($_GET['oid']))
                            if(!empty($_GET['oid']) && preg_match("/^[a-z]+$/i", $_GET['oid']))
                                $rest_search .= " and `oid`='".array_search(strtoupper($_GET['oid']), $hash)."'";
					}
				}
                if (isset($_GET['user']))
                    if(!empty($_GET['user']))
                        if ($app->user->str_check($_GET['user']))
                            $rest_search .= " and `user`='".$_GET['user']."'";
                if (isset($_GET['result']))
                    if(is_numeric($_GET['result']) && intval($_GET['result']) >= 0 && intval($_GET['result']) <= 9)
                        $rest_search .= " and binary `result`='".$rest_result[intval($_GET['result'])]."'";
				if (isset($_GET['cid']))
					$arr = $db->from('Record')->where("`contest`='".intval($_GET['cid'])."'".$rest_search)->limit($Config['STATUS_NUMBER_PER_PAGE'], $start)->order('DESC', 'time')->select('id')->fetch_all();
				else
					$arr = $db->from('Record')->where("`contest`='0'".$rest_search)->limit($Config['STATUS_NUMBER_PER_PAGE'], $start)->order('DESC', 'time')->select('id')->fetch_all();
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
                	<a href='person.php?id=<?php echo $res['user']; ?>'><?php echo $res['user']; ?></a>
                </td>
                <td>
                    <font style='color:#1976D2;text-decoration:underline;cursor:pointer;' onclick="javascript:location.href='view.php?id=<?php
                        echo $res['oid'];
                        if ($is_contest)
                            echo '&cid='.$_GET['cid'];
                    ?>'"> 
                	<?php
						if($is_contest)
							echo $hash[$res['oid']];
						else
							echo $res['oid'];
					?>
                    </font>
                </td>
                <td>
                	<font style='color:#1976D2;text-decoration:underline;cursor:pointer;' onclick="javascript:location.href='result.php?id=<?php
                        echo $res['id'];
                        if ($is_contest)
                            echo '&cid='.$_GET['cid'];
                    ?>'"><?php echo $res['result']; ?></font>
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
                	<?php echo date("Y-m-d H:i:s", $res['time']); ?>
                </td>
            </tr>
                    <?php
				}
			?>
        </table>
        <br /><br />
		<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
		<br /><br />
        </center>
    </body>
</html>
