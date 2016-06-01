<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-06-01 22:29:17
        Filename: Server.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once(dirname(__FILE__).'/../Config.Daemon.php');
	pcntl_signal(SIGCHLD, SIG_IGN);
	foreach ($conf['OJ_LIST'] as $oj)
	{
		$pid = pcntl_fork();
		switch ($pid)
		{
			case -1:
				echo 'Error';
				exit(-1);
			break;
			case 0:
				require_once(dirname(__FILE__).'/'.$oj.'_Server.php');
				exit(0);
			break;
			default:
			break;
		}
	}
?>
