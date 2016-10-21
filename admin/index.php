<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-09 13:09:23
        Filename: index.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once("../App.class.php");
	App::loadMod("User");
	$app = new App();
	$user = new User();
	if (!$user->isLogin())
		redirect("Location: status.php?action=login");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>后台管理 - Alxw CMS</title>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <script language="javascript" src="../Widget/jQuery/jquery-2.1.3.min.js"></script>
        <script language="javascript" src="js/main.js"></script>
    </head>
    <body>
    	<div id="top">▲</div>
        <div id="status" tabindex="0" onforce="javascript:alert(1);">
        	<a href="#" class="status_menu" onclick="menu.open('profile.php')"><img src="img/profile.png" /><?php echo $user->getUser(); ?></a>
            <hr />
            <a href="status.php?action=logout" class="status_menu"><img src="img/logout.png" />退出登录</a>
        </div>
    	<div id="header">
        	<div id="header_left">
            	<a href="index.php" title="后台管理"><img src="img/logo.png" /><font id="logo">后台管理 V2</font></a><a href=".." target="_blank" style="color: #FFF;"> - 访问首页</a><font style="color: #0F0;">&nbsp;&nbsp;&nbsp;&nbsp;Alxw CMS</font>
            </div>
            <div id="header_right" class="animate">
            	<img src="img/status.png" />
            </div>
        </div>
        <div id="container">
        	<div id="navigator">
            	<div id="nav_left" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                	<a href="#" title="新建文章" onclick="menu.open('eedit.php?id=new')"><img src="img/edit.png" /></a>
                    <a href="#" title="新建页面" onclick="menu.open('pedit.php?n=new')"><img src="img/page.png" /></a>
                    <a href="#" title="网站属性" onclick="menu.open('config.php')"><img src="img/config.png" /></a>
                    <a href="#" title="网站设置" onclick="menu.open('setting.php')"><img src="img/setting.png" /></a>
                </div>
                <a href="main.php" target="main"><img id="home" src="img/home.png" /></a>
                <font id="location"></font>
            </div>
            <div id="right">
                <div id="left">
                	<div class="item_parent selected" onclick="menu.open('main.php')">
                        <img src="img/dash.svg" />监控面板
                    </div>
                	<div class="item_parent" onclick="menu.toggle('#problem')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/dash.svg" />题目管理&nbsp;&nbsp;&nbsp;<font style="font-size: 15px;">∨</font>
                    </div>
                    <div id="problem" style="display: none;">
                        <div class="item_children" onclick="menu.open('../manager.php')">VJ题目管理</div>
                        <div class="item_children" onclick="menu.open('../AJC_ProblemManager.php')" <?php if (!file_exists('../classes/AJC_Problem.php')) echo "style='display: none;'";?>>AJC题目管理</div>
                    </div>
                	<div class="item_parent" onclick="menu.open('../contest_edit.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/dash.svg" />比赛管理
                    </div>
                	<div class="item_parent" onclick="menu.open('../theme.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/dash.svg" />主题管理
                    </div>
                    <div class="item_parent" onclick="menu.toggle('#eassy')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/eassy.svg" />文章管理&nbsp;&nbsp;&nbsp;<font style="font-size: 15px;">∨</font>
                    </div>
                    <div id="eassy" style="display: none;">
                        <div class="item_children" onclick="menu.open('eedit.php?id=new')">创建文章</div>
                        <div class="item_children" onclick="menu.open('etype.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>文章分类</div>
                        <div class="item_children" onclick="menu.open('eassy.php')">文章列表</div>
                    </div>
                    <div class="item_parent" onclick="menu.toggle('#page')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/page.svg" />页面管理&nbsp;&nbsp;&nbsp;<font style="font-size: 15px;">∨</font>
                    </div>
                    <div id="page" style="display: none;">
                        <div class="item_children" onclick="menu.open('pedit.php?n=new')">创建页面</div>
                        <div class="item_children" onclick="menu.open('page.php')">页面列表</div>
                    </div>
                    <div class="item_parent" onclick="menu.open('user.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/user.svg" />用户管理
                    </div>
                    <div class="item_parent" onclick="menu.open('file.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/file.svg" />文件管理
                    </div>
                    <div class="item_parent" onclick="menu.open('db.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/db.svg" />DB&nbsp;&nbsp;管理
                    </div>
                    <div class="item_parent" onclick="menu.open('config.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/config.svg" />网站属性
                    </div>
                    <div class="item_parent" onclick="menu.open('setting.php')" <?php if ($user->getPower() != 0) echo "style='display: none;'";?>>
                        <img src="img/setting.svg" />网站设置
                    </div>
                    <div class="item_bottom" onclick="javascript:document.getElementById('cello_link').click();">
                    	<a href="http://www.90its.cn/" target="_blank" id="cello_link"></a>
                       	<img src="img/chrome.png" /> <font id="cello">Cello Studio</font>
                    </div>
                </div>
            	<iframe name="main" id="main" src="main.php" scrolling="no" frameborder="0" width="100%" height="100%"></iframe>
            </div>
        </div>
    </body>
</html>
