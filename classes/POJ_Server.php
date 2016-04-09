<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-31 16:13:44
        Filename: POJ_Server.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	define('DEBUG', true);

	require_once(dirname(__FILE__)."/../App.class.php");
	require_once(dirname(__FILE__)."/POJ_Record.php");

	class POJ_DataPoster_Worker {
		
		private $data = "";
		private $db = NULL;
		private $app = NULL;
		private $geter = NULL;
		private $info = NULL;
		private $pid = "";
		private $lang = "";
		private $user = "";
		private $pass = "";
		private $rid = "";
		
		public function POJ_DataPoster_Worker($user = "skvj01", $pass = "forskvj", $id = "1000", $lang = "0", $code = "", $rid = "")
		{

			if (DEBUG)
				echo "[D] => $user, $pass, $id, $lang, $rid\n";

			//MySQL
			$this->db = new dMySQL();

			//Infomation
			$cookie_file = tempnam("./cookie", "cookie");
			$login_url = "http://poj.org/login";
			$post_fields = "user_id1=".$user."&password1=".$pass."&url=/";
			$this->rid = $rid;
			$this->pid = $id;
			$this->lang = $lang;
			$this->user = $user;
			$this->pass = $pass;
			
			//Login
			$curl = curl_init($login_url); 
    		curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
			$this->data = curl_exec($curl);
			curl_close($curl);
			
			//Submit
			$hint_code = /*"//<ID>".$rid."</ID>\n".*/$code;
			$post_fields = 'problem_id='.$id.'&language='.$lang.'&encoded=1&source='.urlencode($hint_code);
			//print_r(base64_encode($code));
			$curl = curl_init("http://poj.org/submit"); 
    		curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
			$this->data = curl_exec($curl);
			curl_close($curl);
			
			
			@unlink($cookie_file);
			
			//Record Information
			$this->info = array(
					'id' => $rid,
					'user' => $user
				);
			
		}
		
		public function getData()
		{
			return $this->data;
		}
		
		public function getRunID()
		{
			require_once(dirname(__FILE__)."/HTMLParser.php");
			$this->html = new HTMLParser("http://poj.org/status?problem_id=".$this->pid."&user_id=".$this->user."&result=&language=".$this->lang);
			$this->html->loadHTML($this->html->innerHTML('<td width=17%>Submit Time</td></tr>'."\n", "\n".'</table>'));
			//echo "LLL:".$this->rid."\n\n";
			while ($this->html->innerHTML('<tr align=center><td>', '</td>') != "")
			{
				$r_id = $this->html->innerHTML('<tr align=center><td>', '</td>');
				//echo "RID:".$r_id."\n";
				$this->html->loadHTML($this->html->startString('<tr align=center><td>'));
				$t_id = $this->getIdFromSource($r_id);
				//echo "LID:".$t_id."\n\n";
				if ($t_id == $this->rid)
					return $r_id;
			}
			return "";
		}
		
		public function getIdFromSource($RunID)
		{
			//Infomation
			$cookie_file = tempnam("./cookie", "cookie");
			$login_url = "http://poj.org/login";
			$post_fields = "user_id1=".$this->user."&password1=".$this->pass."&url=/";
			
			//Login
			$curl = curl_init($login_url); 
    		curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
			$this->data = curl_exec($curl);
			
			//Get Source
			$curl = curl_init("http://poj.org/showsource?solution_id=".$RunID); 
    		curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
			$src = curl_exec($curl);
			@unlink($cookie_file);
			$th = new HTMLParser();
			$th->loadHTML($src);
			return $th->innerHTML('//&lt;ID&gt;', '&lt;/ID&gt;');
		}
		
	}

	function getList($ll)
	{
		$db = new dMySQL();
		$ret = $db->from('Record')->where('`rid`=\'__\' AND `oj`=\'POJ\'')->order('DESC', 'time')->limit($ll, 0)->select()->fetch_all();
		$db->close();
		return $ret;
	}

	function main()
	{
		
		global $conf;

		while (true)
		{
			//Each loop will create few child process depends on number of account...
			$list = getList(count($conf['ACCOUNT_LIST']['POJ']));
			$pids = array();
			for ($i=0;$i<count($list);++$i)
			{
				//Still a bug, when child prosess exit, it will close mysql connection, and parent process get a error...Orz...
				//Maybe bug fixed...
				$tmp_pid = $pids[] = pcntl_fork();
				switch ($tmp_pid)
				{
					case -1:
						fprintf(STDERR, "[E] => Error fork on record %s\n", $list[$i]['id']);
						exit;
					break;
					case 0:
						$fork_db = new dMySQL();
						$oo_r = $i;
						$oo_u = $conf['ACCOUNT_LIST']['POJ'][$oo_r]['USER'];
						$oo_p = $conf['ACCOUNT_LIST']['POJ'][$oo_r]['PASS'];
						$pdw = new POJ_DataPoster_Worker($oo_u, $oo_p, $list[$i]['tid'], $list[$i]['lang'], $list[$i]['code'], $list[$i]['id']);
						$rrid = $pdw->getRunID();
						if (DEBUG)
							echo '[D] => Run ID is '.$rrid."\n";
						if ($rrid != '')
							$fork_db->set(array(
										'rid' => $rrid,
										'oj_u' => $oo_u,
										'oj_p' => $oo_p
									))->where('`id`=\''.$list[$i]['id'].'\'')->update('Record');
						else
							$fork_db->set(array(
										'rid' => 'NONE',
										'oj_u' => $oo_u,
										'oj_p' => $oo_p
									))->where('`id`=\''.$list[$i]['id'].'\'')->update('Record');
						$pr = new POJ_Record($list[$i]['id']);
						$pr->initMySQL();
						$pr->_getInfo();
						while (!$pr->check())
							$pr->_getInfo();
						$fork_db->close();
						exit;
					break;
					default:
					break;
				}
			}
			foreach ($pids as $item)
				if ($item)
					pcntl_waitpid($item, $status);
			//Sleep 5 seconds
			if (DEBUG)
				echo "[D] => 5s after...\n";
			sleep(5);
		}
	}

	main();
	
?>
