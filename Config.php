<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-11-07 11:02:36
        Filename: ../Config.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	$Config = array(
			'DB_HOST' => '127.0.0.1',
			'DB_USER' => 'root',
			'DB_PASS' => 'sksks',
			'DB_NAME' => 'build_vj',
			'AUTO_USER' => 'root',
			'AUTO_PASS' => 'sksks',
            'PROBLEM_NUMBER_PER_PAGE' => 20,
            'STATUS_NUMBER_PER_PAGE' => 10,
            'CODER_NUMBER_PER_PAGE' => 10
		);
	$sql = NULL;
	function __autoload($class)
	{
		$file = dirname(__FILE__)."/system/".$class."/".$class.".class.php";
		if (file_exists($file))
			require_once($file);
		else
			die("<center><h1>Class not found!</h1></center>");
	}
	function redirect($str)
	{
		header($str);
		die();
	}

	if (!function_exists('mysql_connect'))
		require_once('system/mysql_mysqli.php');

?>
