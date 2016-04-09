<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-31 16:13:36
        Filename: Server.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once('Config.php');
	$pid = array();
	foreach ($conf['OJ_LIST'] as $oj)
	{
		$pid[] = pcntl_fork();
		switch ($pid[count($pid) - 1])
		{
			case -1:
				fprintf(STDERR, "[E] => Fork error on oj %s.\n", $oj);
				exit;
			break;
			case 0:
				require_once($oj.'_Server.php');
				exit;
			default:
			break;
		}
	}
	foreach ($pid as $item)
		if ($item)
			pcntl_waitpid($item, $status);
	echo "Listening...\n";
?>
