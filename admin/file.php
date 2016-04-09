<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-08-28 09:15:10
        Filename: file.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("../App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		header("Location: status.php?action=login");
	if ($user->getPower() != 0)
		header("Location: status.php?action=login");
/*
 FileName : file.php
 Desc : to dir all files on Server. can download files and delete files.
 author :Longwuxu
 email : 874889289@sina.com
 */
 /**
 	Changed by SpringHack
	email: springhack@gmail.com
	QQ: 973349752
 **/
 
function dealString($data)
{
	if (!empty($data))
	{
		$fileType = mb_detect_encoding($data, array(
				'UTF-8',
				'GBK',
				'LATIN1',
				'BIG5',
				'GB2312'
			));
		if ($fileType != 'UTF-8')
			$data = mb_convert_encoding($data, 'UTF-8', $fileType);
	}
	return $data;
}
 
error_reporting(0);
/* 上传文件 */
function upfile($file_var,$tofile,$filepath){
	/* 参数说明:

	*/
	if(!is_writable($filepath)){
		echo"$filepath 目录不存在或不可写";
		return false;
		exit;
	}
	//echo $_FILES["$file_var"]['name'];
	$Filetype=substr(strrchr($_FILES["$file_var"]['name'],"."),1);
	($tofile==='')?($uploadfile = $_FILES["$file_var"]['name']):($uploadfile = $tofile.".".$Filetype);//文件名
	$Array[tofile] = $tofile.'.'.$Filetype;
	$Array[oldfile]= $_FILES["$file_var"]['name'];
	if(!($uploadfile==='')){
		if (!is_uploaded_file($_FILES["$file_var"]['tmp_name'])){
			echo $_FILES["$file_var"]['tmp_name']." 上传失败.";
			return false;
			exit;
		}

		if (!move_uploaded_file($_FILES["$file_var"]['tmp_name'],$filepath.'/'.$uploadfile)){
			echo "上传失败。错误信息:\n";
			print_r($_FILES);
			exit;
		}else{
			return $Array;
		}
	}else{
		return false;
		echo"无法上传";
	}
}
/*删除目录*/
function deletedir($dir)
{
	if(!$handle=@opendir($dir))
	{//检测要打开的目录是否存在
		echo "没有该目录".$dir;
		//die("没有该目录");
	}
	while(false!==($file=readdir($handle)))
	{
		if($file!="."&&$file!="..")
		{
			$file=$dir.DIRECTORY_SEPARATOR.$file;
			if(is_dir($file))
			{
				deletedir($file);
			}
			else
			{
				if(@unlink($file))
				{
					//echo "文件删除成功<br>";
				}
				else
				{
					echo "文件删除失败<br>";
				}
			}
		}
	}
	closedir($handle);
	if(@rmdir($dir))
	{
		echo "<script>alert(\"目录删除成功\"),window.location.href=\"file.php\";</script>";	
	}
	else
	{
		echo "删除失败".$dir;
	}

}
/* 获取文件大小 */
function getSize(&$fs)
{
	if($fs<1024)
	return $fs."Byte";
	elseif($fs>=1024&&$fs<1024*1024)
	return @number_format($fs/1024, 3)." KB";
	elseif($fs>=1024*1024 && $fs<1024*1024*1024)
	return @number_format($fs/1024*1024, 3)." M";
	elseif($fs>=1024*1024*1024)
	return @number_format($fs/1024*1024*1024, 3)." G";
}
// 下载文件
if ($_GET['downfile']) {
	$downfile=$_GET['downfile'];
	if (!@is_file($downfile)) {
		echo "<script>alert(\"你要下的文件不存在\")</script>";
	}
	$filename = basename($downfile);
	$filename_info = explode('.', $filename);
	$fileext = $filename_info[count($filename_info)-1];
	header('Content-type: application/x-'.$fileext);
	header('Content-Disposition: attachment; filename='.$filename);
	header('Content-Description: PHP3 Generated Data');
	readfile($downfile);
	exit;
}

// 删除文件
if(@$_GET['delfile']!="") {
	$delfile=$_GET['delfile'];
	if(file_exists($delfile)) {
		@unlink($delfile);
	} else {
		$exists="1";
		echo "<script>alert(\"文件已不存在\")</script>";
	}
	if(!file_exists($delfile)&&$exists!="1") {
		echo"<script>alert(\"删除成功\"),window.location.href=\"file.php\";</script>";
	} else {
		echo"<script>alert(\"删除失败\")</script>";
	}
}
//删除目录
if(@$_GET['deldir']!="")
{
	$deldir=$_GET['deldir'];
	deletedir($deldir);
}
//编辑文件
$edit_flag=false;
if(@$_GET['editfile']!="")
{
	$flag_show=1;
	$editfile=$_GET['editfile'];
	if(file_exists($editfile))
	{
		$edit_flag=true;
		$handle=fopen($editfile,"r");
		$contentfile=fread($handle,filesize($editfile));
		fclose($handle);
	}
	else
	{ return false;
	echo "<script>alert(\"文件不能编辑\")</script>";
	}

}
else
{
	$flag_show=0;
}
/* 检测当前目录值 */
$CurrentPath	= $_POST['path']?$_POST['path']:($_GET['path']?$_GET['path']:false);
if($CurrentPath===false)
{
	$CurrentPath	= dirname(".");
}
$CurrentPath	= realpath(str_replace('\\','/',$CurrentPath));
/* 检查完毕 */
/* 新建 目录 */
if($_POST['dirname'])
{
	$newdir	= $CurrentPath."/".$_POST['dirname'];
	if(is_dir($newdir))
	{
		echo"<script>alert(\"此目录名已经存在!\")</script>";
		exit;
	}else {
		if(mkdir($newdir,0700))
		{
			echo"<script>alert(\"创建成功!\"),window.location.href=\"file.php\";</script>";
		}else {
			echo "<script>alert(\"创建失败!\")</script>";
		}
	}
}
/* 上传文件 */
if($_POST['upload'])
{
	if(!(upfile("upfiles",$_POST['fname'],$CurrentPath)))
	{
		echo"<script>alert(\"上传失败!\")</script>";
	}else {
		echo "<script>alert(\"上传成功!\")</script>";
	}
}
/* 编辑内容*/
if($_POST['editcontent'])
{
	$path_up=$_POST['path_f'];
	$contents_file_up=$_POST['contents_file'];
	$handle=fopen($path_up,"w");
	if($handle)
	{
		fwrite($handle,$contents_file_up);
		fclose($handle);

		//$url="http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
		//header("location:".$url);
		echo "<script>alert(\"编辑成功\");window.location.href=\"file.php\";</script>";
		 

	}
	else
	{
		return false;
		echo "<script>alert(\"编辑失败\")</script>";
	}

}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>文件管理</title>
<script type="text/javascript">
function edit()
{


   document.getElementById('edit').style.display="";
	
}
</script>
<style type="text/css">
<!--
body {
	font-family: "宋体";
	font-size: 12px;
	margin-left: 0px;
	margin-top: 0px;
}

table {
	font-family: "宋体";
	font-size: 12px;
	text-decoration: none;
}

.bold_blue {
	color: #003399;
	font-weight: bold;
}

input {
	border-right-width: 0.1mm;
	border-bottom-width: 0.1mm;
	border-top-style: none;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: none;
	border-right-color: #CCCCCC;
	border-bottom-color: #CCCCCC;
}
-->
</style>

</head>
<body>
<table width="770" border="0" align="center" cellpadding="5"
	cellspacing="0">
	<tr>
		<td align="center" bgcolor="#BCBCBC"><font color="White">PHP版本：</font><font
			color=red><?php echo PHP_VERSION;?></font> &nbsp;&nbsp;&nbsp;<font
			color="White"> 服务器：</font><font color=red><?php echo php_uname();?></font></td>
	</tr>
	<tr>
		<td bgcolor="#DDDDDD">
		<table width="100%" height="100%" border="0" cellpadding="5"
			cellspacing="2" bgcolor="#339966">
			<tr>
				<form name="form1" method="post" action="">
				<td><span class="bold_blue"><strong>目录选择</strong>：</span> <input
					name="path" type="text" id="path"> <input type="submit"
					name="Submit" value="跳 转"></td>
				</form>
			</tr>
			<tr>
				<form name="form2" method="post" action="">
				<td><span class="bold_blue"><strong>新建目录</strong>：</span> <input
					name="dirname" type="text" id="dirname"> <input type="submit"
					name="Submit" value="建 立"></td>
				</form>
			</tr>
			<form name="form3" method="post" action=""
				enctype="multipart/form-data">
			<tr>
				<td><span class="bold_blue"><strong>上传文件</strong>：</span> <input
					name="upfiles" type="file" id="upfiles"></td>
			</tr>
			<tr>
				<td><span class="bold_blue"><strong> 新文件名</strong>：</span> <input
					name="fname" type="test" id="fname"> <input type="submit"
					name="upload" value="上 传"></td>
			</tr>
			</form>
			<tr>
				<td><span class="bold_blue">当前路径：</span><font color=red><?php echo dealString($CurrentPath);?></font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#DDDDDD">
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td bgcolor="#BCBCBC"><strong>子目录</strong></td>
			</tr>
			<tr>
				<td>
				<table width="100%" border="0" cellpadding="0" cellspacing="5"
					bgcolor="#EFEFEF">
					<tr>
						<td><b>目录名</b></td>
						<td><b>操作</b></td>
					</tr>
					<?php
					$fso=@opendir($CurrentPath);
					while ($file=@readdir($fso)) {
						$fullpath	= "$CurrentPath/$file";
						$is_dir		= @is_dir($fullpath);
						if($is_dir=="1"){
							if($file!=".."&&$file!=".")	{
								echo "<tr bgcolor=\"#EFEFEF\">\n";
								echo "<td>【目录】 <a href=\"?path=".urlencode($CurrentPath)."/".urlencode($file)."\">".dealString($file)."</a></td>\n";
								echo "<td><a href=\"?path=".urlencode($CurrentPath)."&deldir=".urlencode($fullpath)."\">delete</a></td>\n";
								echo "</tr>\n";
							} else {
								if($file=="..")
								{
									echo "<tr bgcolor=\"#EFEFEF\">\n";
									echo "<td>【上级】 <a href=\"?path=".urlencode($CurrentPath)."/".urlencode($file)."\">上级目录</a></td>";
									echo "</tr>\n";
								}
							}
						}
					}
					@closedir($fso);
					?>
				</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#BDBEBD"><strong>文件列表</strong></td>
			</tr>
			<tr>
				<td>
				<table width="100%" border="0" cellpadding="0" cellspacing="5"
					bgcolor="#EFEFEF">
					<tr>
						<td><b>文件名</b></td>
						<td><b>修改日期</b></td>
						<td><b>文件大小</b></td>
						<td><b>操作</b></td>
					</tr>
					<?php
					$flag_file=0;//检测是否有文件
					$fso=@opendir($CurrentPath);
					while ($file=@readdir($fso)) {
						$fullpath	= "$CurrentPath\\$file";
						$is_dir		= @is_dir($fullpath);
						if($is_dir=="0"){
							$flag_file++;
							$size=@filesize("$CurrentPath/$file");
							$size=@getSize($size);
							$lastsave=@date("Y-n-d H:i:s",filemtime("$CurrentPath/$file"));
							echo "<tr bgcolor=\"#EFEFEF\">\n";
							echo "<td>◇ ".dealString($file)."</td>\n";
							echo "  <td>$lastsave</td>\n";
							echo "  <td>$size</td>\n";
							?>
					<td><input type="hidden" id="<?php echo $flag_file."path"?>"
						value="<?php echo $filec;?>"><a
						href="?downfile=<?php echo urlencode($CurrentPath)."/".urlencode($file);?>">下载</a>|<a
						href="?editfile=<?php echo urlencode($CurrentPath)."/".urlencode($file);?>"
						>编辑</a>|<a
						href="?path=<?php echo urlencode($CurrentPath)."&delfile=".urlencode($CurrentPath)."/".urlencode($file);?>">删除</a></td>
						<?php
						//	echo "  <td><a href=\"?downfile=".urlencode($CurrentPath)."/".urlencode($file)."\">下载</a> |<a href=\"?path=".urlencode($CurrentPath)."&delfile=".urlencode($CurrentPath)."/".urlencode($file)."\">删除</a></td>\n";
						echo "</tr>\n";
						}
					}
					if($flag_file==0)
					{
						echo "<tr bgcolor=\"#EFEFEF\">\n";
						echo "<td align=\"center\" colspan=\"3\"><font style=\"color:red;\" size=\"10\">没有文件</font></td>";
						echo "</tr>\n";
					}
					@closedir($fso);
					?>
				</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#BDBEBD"><strong>编辑内容</strong></td>
			</tr>
			<tr>
				<td>
				<div id="edit" <?php if($flag_show==0) {?> style="display: none"
				<?php }?>>
				<table width="100%" border="0" cellpadding="0" cellspacing="5"
					bgcolor="#EFEFEF">
					<form name="edit" method="post" action="">
					<tr>
						<td><input type="hidden" name="path_f"
							value="<?php echo $editfile;?>"></input> <textarea
							id="contents_edit" name="contents_file"
							style="width: 100%; height: 500px; overflow-y: visible;"><?php if($edit_flag){ echo dealString($contentfile);?><?php }else{ echo "no" ;}?>
							</textarea></td>
					</tr>
					<tr>
						<td><input style="background-color: gray" type="submit"
							name="editcontent" value="submit"></input></td>
					</tr>
					</form>
				</table>
				</div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#DDDDDD">
		<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td bgcolor="#BCBCBC"><strong>CopyRight</strong></td>
			</tr>
			<tr>
				<td>
				<table width="100%" border="0" cellpadding="0" cellspacing="5"
					bgcolor="#EFEFEF">
					<tr align="center">
						<td><font size="3">Copyright (C) <?php echo date("Y"); ?> <a href="http://www.90its.cn/"><font size="5"
							color="red"><b>SpringHack</b></font></a> All Rights Reserved .</font></td>
					</tr>
					<tr>
					<td align="right"><a href="file.php"><font color="blue">返回首页</font></a></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>
