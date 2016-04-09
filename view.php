<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-12-08 10:29:33
        Filename: view.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>View Problem</title>
    </head>
    <body>
    	<center>
        <?php require_once("header.php"); ?>
		<?php
			$start = $app->setting->get("startTime", time() + 10);
			if ($start>time())
				die('<center><h1><a href="index.php" style="color: #000000;">Contest not start !</a></h1></center></body></html>');
		?>
        <h1>View Problem</h1>
        <table border="1">
        	<tr>
            	<td width="200">
            		<h2>Submit Code</h2>
            	</td>
                <td width="600">
                	<a href="submit.php?id=<?php echo $_GET['id']; ?>">Submit</a>
                </td>
            </tr>
            <?php
                require_once("api.php");
                require_once("classes/Problem.php");
                $db = new MySQL();
                $info = $db->from("Problem")->where("`id` = '".$_GET['id']."'")->select()->fetch_one();
                $pro = new Problem($info['pid'], $info['oj']);
                $pro_info = $pro->getInfo();
                foreach ($pro_info as $key => $val)
                {
                    echo "<tr><td width='200'><h2>".$key."</h2></td><td width='800'>";
                    if (strstr($key, "sample_"))
                        echo "<pre>".$val."</pre></td></tr>";
                    else
                        echo $val."</td></tr>";
                }
            ?>
            </table>
            <br /><br />
        </center>
    </body>
</html>
