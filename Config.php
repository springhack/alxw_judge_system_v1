<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-31 02:35:49
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
			'AUTO_PASS' => 'sksks'
		);
	$sql = NULL;
	function __autoload($class)
	{
		$file = dirname(__FILE__)."/".$class."/".$class.".class.php";
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
?>
