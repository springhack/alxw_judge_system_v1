<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-02-01 12:27:05
        Filename: admin/user.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("../App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	if ($user->getPower() != 0)
		header("Location: status.php?action=login");
	if (isset($_GET['action']) || isset($_GET['user']))
	{
		if (!$user->str_check($_GET['user']))
			header("Location: error.php");
		if ($_GET['action'] == "delete")
		{
			$user->userDelete($_GET['user']);
			echo "<script language=\"javascript\">alert('删除成功!');history.back(-1);</script>";
			die();
		}
		if ($_GET['action'] == "up")
		{
			$user->userRenew($_GET['user'], $user->getPass($_GET['user']), "", 0);
			echo "<script language=\"javascript\">alert('提权成功!');history.back(-1);</script>";
			die();
		}
		if ($_GET['action'] == "down")
		{
			$user->userRenew($_GET['user'], $user->getPass($_GET['user']), "", 1);
			echo "<script language=\"javascript\">alert('降权成功!');history.back(-1);</script>";
			die();
		}
	}
	$limit = isset($_GET['page'])?(intval($_GET['page']) - 1)*20:"0";
	$list = $user->getUserList(20, $limit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>用户管理</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
    </head>
    <body>
    	<center>
        	<br />
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="200">
                        账号
                    </td>
                    <td width="200">
                        密码  
                    </td>
                    <td width="65">
                        权限  
                    </td>
                    <td width="90">
                    	操作
                    </td>
                </tr>
                <?php for ($i=0;$i<count($list);++$i) { ?>
                    <tr>
                        <td>
                            <?php echo $list[$i]; ?>
                        </td>
                        <td>
                            <?php echo $user->getPass($list[$i]); ?>
                        </td>
                        <td>
                            <?php
                            	if ($user->getPower($list[$i]) == 0)
									echo "管理员";
								else
									echo "普通用户";
							?>
                        </td>
                        <td>
                        	<?php
                            	if ($user->getPower($list[$i]) == 0)
									echo '<a href="user.php?action=down&user='.$list[$i].'">降权</a>';
								else
									echo '<a href="user.php?action=up&user='.$list[$i].'">提权</a>';
							?> | <a href="user.php?action=delete&user=<?php echo $list[$i]; ?>">删除</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </center>
    	<script language="javascript" src="../Widget/pageSwitcher/pageSwitcher.js"></script>
    </body>
</html>
