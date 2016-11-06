<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-06-01 22:25:41
        Filename: Server.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	require_once(dirname(__FILE__).'/../../Config.Daemon.php');

	chdir(dirname(__FILE__));

	$Running = array();

	function getRunning()
	{
		global $conf, $Running;
		$id = `id -u`;
		if (trim($id) != '0')
			die('Must root can run me !'.PHP_EOL);
		$ps = `ps aux`;
		foreach ($conf['OJ_LIST'] as $oj)
		{
			if (strstr($ps, $oj.'_Server.py'))
				$Running[] = $oj;
		}
	}

	function start()
	{
		global $conf, $Running;
		getRunning();
		pcntl_signal(SIGCHLD, SIG_IGN);
		foreach ($conf['OJ_LIST'] as $oj)
		{
			if (!in_array($oj, $Running))
			{
				$pid = pcntl_fork();
				switch ($pid)
				{
					case -1:
						echo $oj.' failed.'.PHP_EOL;
						exit(-1);
					break;
					case 0:
						echo $oj.' done.'.PHP_EOL;
						require_once(dirname(__FILE__).'/'.$oj.'_Server.php');
						exit(0);
					break;
					default:
					break;
				}
			}
		}
	}

	function stop()
	{
		global $Running;
		getRunning();
		$pid = array();
		$ps = explode(PHP_EOL, `ps axu`);
		foreach ($ps as $proc)
			foreach ($Running as $oj)
				if (strstr($proc, $oj))
					$pid[] = explode(' ', $proc);
		foreach ($pid as $proc)
		{
			$i = 0;
			while (!is_numeric($proc[$i]) && $i < count($proc))
				++$i;
			$p = $proc[$i];
			echo "Killing pid $p ...".PHP_EOL;
			echo `kill -9 $p`;
		}
	}

	function restart()
	{
		global $Running;
		stop();
		sleep(5);
		$Running = array();
		sleep(1);
		//May not start some oj, still have to run again
		start();
	}

	function status()
	{
		global $Running;
		getRunning();
		if (count($Running) == 0)
			echo 'No oj is running.'.PHP_EOL;
		else
			foreach ($Running as $oj)
				echo $oj.' is rinning.'.PHP_EOL;
	}

	if (count($argv) < 2)
		echo "Usage: $argv[0] {start|stop|restart|status}".PHP_EOL;
	else {
        if (function_exists($argv[1]))
    		$argv[1]();
        else
		    echo "Usage: $argv[0] {start|stop|restart|status}".PHP_EOL;
    }

?>
