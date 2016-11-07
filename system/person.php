<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-11-07 11:03:05
        Filename: top.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	if (!file_exists('.install'))
	{
		header('Location: Install.php');
		die();
	}
	require_once('api.php');
	$db = new MySQL();
    if (!isset($_GET['id']))
    {
        header('Location: ..');
        exit(0);
    }
    if (!$app->user->str_check($_GET['id']))
    {
        header('Location: ..');
        exit(0);
    }
    $info = $db->from('Users')->where("`user`='".$_GET['id']."'")->select()->fetch_one();
    if (!$info)
    {
        header('Location: ..');
        exit(0);
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Problem List</title>
    </head>
    <body>
    	<center>
        	<?php require_once("header.php"); ?>
            <h3>关于 <?php echo $info['user']; ?> 的一切都在这里了－－</h3>
            <table>
                <tr>
                    <td>
                        User ID
                    </td>
                    <td>
                        Accepted
                    </td>
                    <td>
                        Submissions
                    </td>
                    <td>
                        Ratio
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $info['user']; ?>
                    </td>
                    <td>
                        <?php echo $info['ac']; ?>
                    </td>
                    <td>
                        <?php echo $info['su']; ?>
                    </td>
                    <td>
                        <?php echo intval($info['ac']*100/$info['su']); ?>%
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td width='100'>
                        Quote
                    </td>
                    <td><?php echo unserialize($info['json'])['quote']; ?></td>
                </tr>
                <tr>
                    <td>
                        Problems
                    </td>
                    <td><?php
                        $list = explode(' ', $info['plist']);
                        $list = array_filter($list);
                        sort($list);
                        foreach ($list as $pid)
                            echo '<a href=\'view.php?id='.$pid.'\'>'.$pid.'</a> &nbsp; ';
                    ?></td>
                </tr>
            <table>
                <tr>
                    <td>
                        Famous Quotes
                    </td>
                </tr>
                <tr>
                    <td><h4><?php echo $Config['RAND_QUOTE'][rand(0, count($Config['RAND_QUOTE']) - 1)]; ?></h4></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        广告时间
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href='https://github.com/springhack' target='_blank'>改善这个开源项目</a><br />
                        <a href='http://www.dosk.win/' target='_blank'>膜一下开发者－－</a><br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>
