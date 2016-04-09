<html>
<head>
<meta charset="utf-8" />
<title>文件上传实例</title>
</head>
<body>


<?php
	require_once(dirname(__FILE__)."/../../App.class.php");
	App::loadMod("User");
	App::loadMod("Setting");
	$app = new App();
	$user = new User();
	$setting = new Setting();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	if ($user->getPower() != 0 && $setting->get("UploadOpen", "on") != "on")
		die("<script>alert('服务器禁止上传!');</script>");

include("upload.php");                 # 加入类文件
$f_upload = new upload_file;             # 创建对象
$f_upload->set_file_type($_FILES['src']['type']);   # 获得文件类型
$f_upload->set_file_name($_FILES['src']['name']);   # 获得文件名称
$f_upload->set_file_size($_FILES['src']['size']);   # 获得文件尺寸
$f_upload->set_upfile($_FILES['src']['tmp_name']);  # 服务端储存的临时文件名
$f_upload->set_size(1048576);               # 设置最大上传KB数
$f_upload->flash_directory=urldecode($_GET['curl']);
//$f_upload->set_base_directory("uploadImages");    # 文件存储根目录名称
$f_upload->set_url("index.php?curl=".$_GET['curl']);             # 文件上传成功后跳转的文件
$f_upload->save();                  # 保存文件

 ?>

</body></html>