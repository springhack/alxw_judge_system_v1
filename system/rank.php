<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-12-13 11:19:27
        Filename: rank.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
    require_once("api.php");
	$db = new MySQL();
	if (isset($_GET['cid']))
	{
		$res = $db->from('Contest')->where("`id`='".intval($_GET['cid'])."'")->select()->fetch_one();
		if (!$res)
			die('<center><h1><a href="index.php" style="color: #000000;">No such contest !</a></h1></center>');
		@session_start();
		if (!empty($res['password']))
		{
			if (!isset($_SESSION['contest_'.intval($_GET['cid'])]))
			{
				header('Location: password.php?cid='.intval($_GET['cid']));
				die();
			} else {
				if ($res['password'] != $_SESSION['contest_'.intval($_GET['cid'])])
				{
					header('Location: password.php?cid='.intval($_GET['cid']));
					die();
				}
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="refresh" content="30" />
        <title>Rank List</title>
    </head>
    <body>
    	<?php
        	function secToTime($times){
				$result = '00:00:00';
				if ($times>0) {
					$hour = floor($times/3600);
					$minute = floor(($times-3600 * $hour)/60);
					$second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
					$result = $hour.':'.$minute.':'.$second;
				}
				return $result;
			}
            if (!isset($_GET['cid']))
                die('<center><h1><a href="index.php" style="color: #000000;">No such contest !</a></h1></center></body></html>');
		?>
    	<?php
			$res_t = $db->from('Contest')->where("`id`='".$_GET['cid']."'")->select()->fetch_one();
			if (!$res_t)
                die('<center><h1><a href="index.php" style="color: #000000;">No such contest !</a></h1></center></body></html>');
			$time = intval($res_t['cache']);
			$list = unserialize($res_t['rank']);
			if ((time() - intval($time)) > 30)
			{
                $u_list = $db->query("select distinct Record.user,Users.json from Record left join Users on Record.user=Users.user where Record.contest=".$_GET['cid'].";")->fetch_all();
			    $res_all = $db->from('Record')->where("`contest`='".$_GET['cid']."'")->select()->order('ASC', 'time')->fetch_all();
				$p_list = explode(',', $res_t['list']);
				$start = $res_t['time_s'];
                $undeal = array();
				for ($i=0;$i<count($u_list);++$i)
				{
					$undeal['ss_'.$u_list[$i]['user']] = array(
							'user' => $u_list[$i]['user'],
                            'nick' => unserialize($u_list[$i]['json'])['nick'],
							'time' => 0,
							'deal' => 0,
							'do' => 0
						);
					for ($j=0;$j<count($p_list);++$j)
					{
						$undeal['ss_'.$u_list[$i]['user']]['ss_'.$p_list[$j]] = array(
								'pid' => $p_list[$j],
								'result' => 'no',
								'time' => 0,
								'wrong' => 0
							);
					}
				}
                foreach ($res_all as $item)
                {
                    if ($item['result'] == 'Accepted')
                    {
                        if ($undeal['ss_'.$item['user']]['ss_'.$item['oid']]['result'] != 'yes')
                        {
                            $undeal['ss_'.$item['user']]['ss_'.$item['oid']]['result'] = 'yes';
                            $undeal['ss_'.$item['user']]['ss_'.$item['oid']]['time'] = $item['time'] - $start;
                        }
                        $undeal['ss_'.$item['user']]['do']++;
                    } else {
                        if ($item['result'] != 'System Error')
                        {
                            if ($undeal['ss_'.$item['user']]['ss_'.$item['oid']]['result'] != 'yes')
                            {
                                $undeal['ss_'.$item['user']]['ss_'.$item['oid']]['wrong']++;
                                $undeal['ss_'.$item['user']]['do']++;
                            }
                        }
                    }
                }
				$list = array();
			    for ($i=0;$i<count($u_list);++$i)
				{
					$list[$i] = $undeal['ss_'.$u_list[$i]['user']];
					for ($j=0;$j<count($p_list);++$j)
                    {
						$list[$i][$j] = $undeal['ss_'.$u_list[$i]['user']]['ss_'.$p_list[$j]];
                        unset($list[$i]['ss_'.$u_list[$i]['user']]);
                        if ($list[$i][$j]['result'] == 'yes')
                        {
    					    $list[$i]['time'] += ($list[$i][$j]['time'] + $list[$i][$j]['wrong']*1200);
                            $list[$i]['deal']++;
                        }
                    }
				}
				$t_list = array();
				for ($t=0;$t<count($list);++$t)
				{
					if ($list[$t]['do'] != 0)
						$t_list[] = $list[$t];
				}
				$list = $t_list;
				unset($t_list);
				for ($i=0;$i<count($list)-1;++$i)
					for ($j=$i+1;$j<count($list);++$j)
					{
						if ($list[$i]['deal'] < $list[$j]['deal'])
						{
							$tmp = $list[$i];
							$list[$i] = $list[$j];
							$list[$j] = $tmp;
						}
						if ($list[$i]['deal'] == $list[$j]['deal'])
						{
							if ($list[$i]['time'] != 0 || $list[$j]['time'] != 0)
								if (($list[$i]['time'] > $list[$j]['time']) || ($list[$i]['time'] == 0))
								{
									$tmp = $list[$i];
									$list[$i] = $list[$j];
									$list[$j] = $tmp;
								}
						}
					}
                $rank = serialize($list);
                if (!get_magic_quotes_gpc())
                    $rank = addslashes($rank);
				$db->set(array(
                            'rank' => $rank,
							'cache' => time()
                        ))->where("`id`='".$_GET['cid']."'")
                        ->update('Contest');
                print_r($db->error());
			}
		?>
        <center>
        	<?php require_once("header.php"); ?>
            <script src='javascript/FileSaver.js'></script>
            <script src='javascript/XML.js'></script>
        	<h1>Rank List</h1>
            <p><a hred='#' id='export' style='cursor: pointer;'>Export CSV</a></p>
    		<table data-type="rank">
            	<tr data-type="rank" style="color: #FFF; background-color: #0995C4;">
                	<td data-type="rank">
                    	Nick Name
                    </td>
                    <td data-type="rank" width="40" align="center">
                        Deal
                    </td>
                    <td data-type="rank" width="40" align="center">
                        Time
                    </td>
                    <?php
                    	for ($i=1;$i<=count(explode(',', $db->from("Contest")->where("`id`='".$_GET['cid']."'")->select("list")->fetch_one()['list']));++$i)
							echo '<td data-type="rank" align="center" width="40">'.chr(64 + $i).'</td>';
					?>
                </tr>
            	<?php
                	for ($i=0;$i<count($list);++$i)
					{
						echo '<tr data-type="rank"'.(($i%2)?' style="background-color: #CEFDFF;"':'').'><td data-type="rank" style=" border-bottom: 1px dotted #CCCCCC;" width="200">'.$list[$i]['nick'].'</td>';
						echo '<td data-type="rank" align="center" style=" border-bottom: 1px dotted #CCCCCC;">'.$list[$i]['deal'].'</td>';
						echo '<td data-type="rank" align="center" style=" border-bottom: 1px dotted #CCCCCC;">'.secToTime($list[$i]['time']).'</td>';
						foreach ($list[$i] as $key => $val)
							if (!is_string($key))
							{
								if ($list[$i][$key]['result'] == 'yes')
									echo '<td data-type="rank" align="center" style="background-color: #0F0; border-bottom: 1px dotted #CCCCCC;">'.secToTime($list[$i][$key]['time']).'<br />';
								else
									if ($list[$i][$key]['wrong'] != 0)
										echo '<td data-type="rank" align="center" style="background-color: #F00; border-bottom: 1px dotted #CCCCCC;">';
									else
										echo '<td data-type="rank" style=" border-bottom: 1px dotted #CCCCCC;" align="center">';
								if ($list[$i][$key]['wrong'] != 0)
									echo '-'.$list[$i][$key]['wrong'];
								echo '</td>';
							}
					}
				?>
        	</table>
            <br />
            <br />
        </center>
    </body>
</html>
