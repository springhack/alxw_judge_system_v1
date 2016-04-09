<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-09 15:25:57
        Filename: index.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (!file_exists('.install'))
	{
		header('Location: Install.php');
		die();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Problem List</title>
    </head>
    <body>
    	<center>
        	<?php require_once("header.php"); ?>
        	<h1>Problems List</h1>
    	<?php
			$sstart = isset($_GET['page'])?(intval($_GET['page'])-1)*100:0;
			require_once("api.php");
			$db = new MySQL();
			if ($db->query("SHOW TABLES LIKE 'Problem'")->num_rows() != 1)
			{
				$db->struct(array(
						'id' => 'text',
						'pid' => 'text',
						'title' => 'text',
						'oj' => 'text'
					))->create("Problem");
			}
			$start = $app->setting->get("startTime", time() + 10);
			if ($start>time())
				die('<center><h1><a href="index.php" style="color: #000000;">Contest not start !</a></h1></center></body></html>');
			$list = $db->from("Problem")->limit(100, $sstart)->select()->fetch_all();
			echo "<table border='1'><tr><td width='100'>Problem ID</td><td width='500'>Problem Title</td></tr>";
			for ($i=0;$i<count($list);++$i)
				echo "<tr><td width='100'>".(intval($i)+1)."</td><td width='500'><a href='view.php?id=".$list[$i]['id']."'>".$list[$i]['title']."</a></td></tr>";
			echo "</table>";
		?><br /><br />
		<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
		<br /><br />
        </center>
    </body>
</html>
