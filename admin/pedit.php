<?php
	require_once("../App.class.php");
	App::loadMod("User");
	App::loadMod("Page");
	App::loadMod("Setting");
	$app = new App();
	$user = new User();
	$page = new Page();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	if ($user->getPower() != 0)
		header("Location: status.php?action=login");
	if (!isset($_GET['n']))
		header("Location: error.php");
	if ($_GET['n'] == "new")
		$t = false;
	else {
		$t = true;
		if (!$user->str_check($_GET['n']))
			redirect("Location: error.php");
		$post = $page->getPage($_GET['n']);
		if (!$post)
			redirect("Location: error.php");
	}
	if (isset($_POST['submit']))
	{
		if ($_GET['n'] == "new")
			$id = $page->createPage($_POST['name'], $_POST['title'], $user->getUser(), $_POST['post']);
		else
			$id = $page->updatePage($_POST['name'], $_POST['title'], $user->getUser(), $_POST['post']);
		if ($id == false)
			header("Location: error.php");
		else
			header("Location: pedit.php?n=".$id);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>页面管理 > <?php
        	if ($t)
				echo "页面:".$post['name'];
			else
				echo "新建页面";
		?></title>
        <script type="text/javascript" src="../Widget/jQuery/jquery-1.4.4.min.js"></script>
		<script charset="utf-8" src="../Widget/KindEditor/kindeditor-min.js"></script>
		<script charset="utf-8" src="../Widget/KindEditor/lang/zh_CN.js"></script>
        <script language="javascript">
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="post"]', {
					allowFileManager : true
				});
			});
		</script>
        <link rel="stylesheet" href="css/frame.css" type="text/css" />
    </head>
    <body>
    	<center>
        	<br />
        	<form action="pedit.php?n=<?php
            	echo $_GET['n'];
			?>&hash=<?php echo uniqid(); ?>" method="post">
            	<label>名称：</label><input <?php
                	if ($t)
						echo 'disabled="disabled"';
				?> type="text" name="name" value="<?php
                	if ($t)
						echo $post['name']
				?>" />
                <?php if ($t) {?>
                	<input type="hidden" name="name" value="<?php echo $post['name']; ?>" />
                <?php } ?>
            	<label>标题：</label><input size="100" type="text" name="title" value="<?php
                	if ($t)
						echo $post['title'];
				?>" />&nbsp;<input type="submit" name="submit" onclick="editor.sync();return true;" value="Submit!" /><br /><br />
                <textarea cols="150" rows="28" name="post"><?php
                	if ($t)
						echo $post['content']; 
				?></textarea><br />
            </form>
            <br />
            Powered by <a href="http://www.90its.cn/" target="_blank">Cello Studio</a> Cpoyright&copy; 2014 - <?php echo date("Y"); ?>
            <br />
            <br />
            <br />
        </center>
    </body>
</html>