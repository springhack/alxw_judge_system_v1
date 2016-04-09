<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-02-01 12:35:12
        Filename: resource.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	if (!file_exists('theme/js'))
		file_put_contents('theme/js', file_get_contents('theme/default/js.js'));
	if (!file_exists('theme/css'))
		file_put_contents('theme/css', file_get_contents('theme/default/css.css'));

	if (isset($_GET['type']))
	{
		switch ($_GET['type'])
		{
			case 'js':
				header('Content-type: javascript/js');
				echo file_get_contents('theme/js');
			break;
			case 'css':
				header('Content-type: text/css');
				echo file_get_contents('theme/css');
			break;
			default:
			break;
		}
	}
?>
