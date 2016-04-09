<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-08-28 09:15:46
        Filename: url.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (!isset($_GET['u']))
		die();
	header("Location: ../Widget/fileUpload".$_GET['u']);
?>
