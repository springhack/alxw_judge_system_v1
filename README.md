# AlxwVJ
An opensource virtual judge system base on skvj.
======

AlxwVJ is an GPL FreeSoftware.

AlxwVJ 是采用GPL的自由软件。


注意：基于本项目源码从事科研、论文、系统开发，"最好"在文中或系统中表明来自于本项目的内容和创意，否则所有贡献者可能会鄙视你和你的项目。
使用本项目源码请尊重程序员职业和劳动

PS: GPL保证你可以合法忽略以上注意事项但不能保证你不受鄙视，呵呵。

新用户必看 README 和 FAQ

    快速安装指南：
    1、安装Ubuntu
    2、执行如下命令
        sudo apt-get update
        sudo apt-get install git apache2 mysql-server php5 php5-curl
        sudo a2enmod rewrite
        service apache2 restart
        sudo git clone https://github.com/springhack/alxwvj.git /var/www/html/alxwvj
        cd /var/www/html/alxwvj
        sudo nohup php classes/Server.php &
    3、安装后访问服务器80端口上的Web服务alxwvj目录
        例如 w3m http://localhost/alxwvj
        
使用上需要帮助，请访问我的博客或联系我的邮箱。

Linux新手请看鸟哥的私房菜http://vbird.dic.ksu.edu.tw/linux_basic/linux_basic.php

目前维护者:	SpringHack	springhack@live.cn	http://blog.90its.cn/

最新更新

    添加多比赛支持
    更改默认主题为Material Design
    更新判题逻辑部分代码
    更名为AlxwVJ，二次重开发
    多进程优化，判题提速100%
    提交界面代码亮显

AlxwVJ特性

    开源 全部采用开源技术，不仅仅是提供源代码，搭建AlxwVJ不需要购买任何商业软件。
    采用成熟的Linux系统平台，通过目录锁定和用户锁定避免恶意答案损害系统。
    可以将Web服务器、数据库服务器、判题服务器分机架设，支持多台判题服务器同时工作。
    管理员可以完全通过Web平台添加题目，包括测试数据也可以同时添加。
    极低的系统需求，曾在AR9331/64M/16M的路由器上无故障运行。
    原生支持64位系统 amd64/x86-64bit

Who Used the System

发源地：

    沈阳航空航天大学 上线时间 2015年8月23日 

部分截图：
![image](http://blog.90its.cn/img/Index.png)
![image](http://blog.90its.cn/img/Login.png)
![image](http://blog.90its.cn/img/Rank.png)
![image](http://blog.90its.cn/img/Status.png)
![image](http://blog.90its.cn/img/Panel.png)
