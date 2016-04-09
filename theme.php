<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-02-01 12:59:48
        Filename: theme.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		redirect("Location: status.php?action=login");

	function my_del($path)
	{
	    if(is_dir($path))
	    {
            $file_list= scandir($path);
            foreach ($file_list as $file)
                if($file != '.' && $file != '..')
                   my_del($path.'/'.$file);
            @rmdir($path);     
	    } else {
			@unlink($path);
	    }
	}

	if (isset($_GET['action']) && isset($_GET['name']))
	{
		switch ($_GET['action'])
		{
			case 'use':
				@file_put_contents('theme/js', file_get_contents('theme/'.$_GET['name'].'/js.js'));
				@file_put_contents('theme/css', file_get_contents('theme/'.$_GET['name'].'/css.css'));
			break;
			case 'del':
				@my_del('theme/'.$_GET['name']);
			break;
			default:
			break;
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>主题管理</title>
        <link rel="stylesheet" href="admin/css/frame.css" type="text/css" />
	</head>
	<body>
		<center>
			<br />
			<br />
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width='200'>
						主题名称
					</td>
					<td width='200'>
						操作
					</td>
				</tr>
				<?php
					$dir = opendir('theme');
					$arr = array(
							'.',
							'..',
							'css',
							'js'
						);
					while ($file = readdir($dir))
					{
						if (!in_array($file, $arr))
						{
							echo '<tr><td width=200>';
							echo $file;
							echo '</td><td width=200>';
							echo '<a href="theme.php?action=use&name='.$file.'">使用主题</a>&nbsp;|&nbsp;<a href="theme.php?action=del&name='.$file.'">删除主题</a>';
							echo '</td></tr>';
						}
					}
				?>
			</table>
		</center>
	</body>
</html>
