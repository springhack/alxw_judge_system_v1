<?php
	require_once("../App.class.php");
	App::loadMod("User");
	App::loadMod("Page");
	App::loadMod("Setting");
	$app = new App();
	$user = new User();
	$page = new Page();
	if (!$user->isLogin())
		redirect("Location: status.php?action=login");
	if ($user->getPower() != 0)
		redirect("Location: status.php?action=login");
	if (isset($_GET['action']) || isset($_GET['name']))
		if ($_GET['action'] == "delete")
		{
			if (!$user->str_check($_GET['name']))
				redirect("Location: error.php");
			$page->deletePage($_GET['name']);
			echo "<script language=\"javascript\">alert('删除成功!');history.back(-1);</script>";
			die();
		}
	$limit = isset($_GET['page'])?(intval($_GET['page']) - 1)*20:"0";
	$list = $page->getList($limit, 20);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>页面管理 > 页面列表</title>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
    </head>
    <body>
    	<center>
        	<br />
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="150">
                        名称
                    </td>
                    <td width="400">
                        标题  
                    </td>
                    <td width="100">
                        作者  
                    </td>
                    <td width="150">
                        时间  
                    </td>
                    <td width="90">
                    	操作
                    </td>
                </tr>
                <?php
                    for ($i=0;$i<count($list);++$i) {
                        $post = $page->getPage($list[$i]);
                ?>
                    <tr>
                        <td>
                            <?php echo $list[$i]; ?>
                        </td>
                        <td>
                            <a href="../view.php?id=<?php
                            	echo $list[$i];
							?>" target="_blank"><?php echo $post['title']; ?></a>
                        </td>
                        <td>
                            <?php echo $post['author']; ?>
                        </td>
                        <td>
                            <?php echo date("Y-M-d H:i", $post['time']); ?>
                        </td>
                        <td>
                        	<a href="pedit.php?n=<?php echo $list[$i]; ?>">编辑</a> | <a href="page.php?action=delete&name=<?php echo $list[$i]; ?>">删除</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </center>
    	<script language="javascript" src="../Widget/pageSwitcher/pageSwitcher.js"></script>
    </body>
</html>