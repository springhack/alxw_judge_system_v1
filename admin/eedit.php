<?php
	require_once("../App.class.php");
	App::loadMod("User");
	App::loadMod("Eassy");
	App::loadMod("Setting");
	$app = new App();
	$user = new User();
	$eassy = new Eassy();
	$setting = new Setting();
	if (!$user->isLogin())
		redirect("Location: status.php?action=login");
	if (!isset($_GET['id']))
		redirect("Location: error.php");
	if ($_GET['id'] == "new")
	{
		$t = false;
		$post['type'] = "草稿";
	} else {
		$t = true;
		if (!$user->str_check($_GET['id']))
			header("Location: error.php");
		$post = $eassy->getEassy($_GET['id']);
		if (!$post)
			header("Location: error.php");
		if ($user->getPower() != 0)
			if ($user->getUser() != $post['author'])
				redirect("Location: error.php");
	}
	$type = unserialize($setting->get("EassyType"));
	if ($post['type'] != "草稿")
	{
		$flag = false;
		for ($i=0;$i<count($type);++$i)
			if (!empty($type[$i]))
				$flag = $flag || ($type[$i] == $post['type']);
		if (!$flag)
			redirect("Location: error.php");
	}
	if (isset($_POST['submit']))
	{
		if ($user->getPower() != 0)
			$_POST['type'] = "草稿";
		if ($_GET['id'] == "new")
			$id = $eassy->createEassy($_POST['title'], $user->getUser(), $_POST['type'], $_POST['post']);
		else
			$id = $eassy->updateEassy($_GET['id'], $_POST['title'], $user->getUser(), $_POST['type'], $_POST['post']);
		if ($id == false)
			header("Location: error.php");
		else
			header("Location: eedit.php?id=".$id);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>文章管理 > <?php
        	if ($t)
				echo "文章:".$post['title'];
			else
				echo "新建文章";
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
        	<form action="eedit.php?id=<?php
            	echo $_GET['id'];
			?>&hash=<?php echo uniqid(); ?>" method="post">
            	<label>分类：</label><select id="hehe" name="type">
                						<option value="草稿">草稿</option>
                                        <?php
                                        	for ($i=0;$i<count($type);++$i)
												if (!empty($type[$i]))
												{
													$tmp = '<option value="'.$type[$i].'"';
													if ($type[$i] == $post['type'])
														$tmp .= ' selected="selected"';
													$tmp .= '>'.$type[$i].'</option>';
													echo $tmp;
												}
										?>
                					</select>
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