<?php
	if (!isset($_GET['u']))
		die();
	header("Location: Widget/fileUpload".$_GET['u']);
?>