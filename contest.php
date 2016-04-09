<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-09 20:40:56
        Filename: contest.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Contests List</title>
    </head>
    <body>
    	<center>
        	<?php require_once("header.php"); ?>
        	<h1>Contests List</h1>
    	<?php
			$sstart = isset($_GET['page'])?(intval($_GET['page'])-1)*10:0;
			require_once("api.php");
			$db = new MySQL();
			if ($db->query("SHOW TABLES LIKE 'Contest'")->num_rows() != 1)
			{
				$db->struct(array(
						'id' => 'text',
						'title' => 'text',
						'list' => 'text',
						'time_s' => 'text',
						'time_e' => 'text',
						'password' => 'text',
						'rank' => 'text'
					))->create("Contest");
			}
			$list = $db->from("Contest")->limit(10, $sstart)->select()->fetch_all();
			echo "<table border='1'><tr><td width='100'>Problem ID</td><td width='200'>Problem Title</td><td width='150'>Start Time</td><td width='150'>End Time</td></tr>";
			for ($i=0;$i<count($list);++$i)
				echo "<tr><td width='100'>".$list[$i]['id']."</td><td width='200'><a href='index.php?cid=".$list[$i]['id']."'>".$list[$i]['title']."</a></td><td>".date('Y-m-d H:i:s', $list[$i]['time_s'])."</td><td>".date('Y-m-d H:i:s', $list[$i]['time_e'])."</td></tr>";
			echo "</table>";
		?><br /><br />
		<script language="javascript" src="Widget/pageSwitcher/pageSwitcher.js"></script>
		<br /><br />
        </center>
    </body>
</html>
