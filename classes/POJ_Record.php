<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-31 03:03:47
        Filename: POJ_Record.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	
	class POJ_Record {
		
		private $db = NULL;
		private $res = "";
		private $id = "";
		
		//Patch of record
		private $html;
		private $data;
		private $user;
		private$pass;
		private $rid;
		//Patch end
		
		//Common construct
		public function POJ_Record($id)
		{
			$this->id = $id;
			$this->rid = $id;
		}

		//Only for cli
		public function initMySQL()
		{
			$this->db = new dMySQL();
			$this->res = $this->db->from("Record")->where("`id` = '".$this->id."'")->select()->fetch_one();
			$this->user = $this->res['oj_u'];
			$this->pass = $this->res['oj_p'];
		}

		//Only for cli
		public function check()
		{
			if ($this->res['result'] != 'N/A'
					&& $this->res['result'] != 'Running & Judging'
					&& $this->res['result'] != 'Waiting'
					&& $this->res['result'] != 'Compiling')
				return true;
			else
				return false;
		}
		
		//For view
		public function getInfo()
		{
			$this->db = new MySQL();
			$this->res = $this->db->from("Record")->where("`id` = '".$this->id."'")->select()->fetch_one();
			return $this->res;
		}

		//Only for cli
		public function _getInfo()
		{
			if ($this->res['result'] != 'N/A'
				&& $this->res['result'] != 'Running & Judging'
				&& $this->res['result'] != 'Waiting'
				&& $this->res['result'] != 'Compiling')
			return $this->res;
			//Patch
			if ($this->res['rid'] == '__')
				return $this->res;
			if ($this->res['rid'] == 'NONE')
			{
				$this->db->set(array(
						'memory' => '0K',
						'long' => '0MS',
						'lang' => 'Unknown',
						'result' => 'Submit Error'
					))->where("`id` = '".$this->id."'")->update("Record");
				$this->res = $this->db->from("Record")->where("`id` = '".$this->id."'")->select()->fetch_one();
				return $this->res;
			}
			require_once(dirname(__FILE__)."/HTMLParser.php");
			//Infomation
			$cookie_file = tempnam("./cookie", "cookie");
			$login_url = "http://poj.org/login";
			$post_fields = "user_id1=".$this->res['oj_u']."&password1=".$this->res['oj_p']."&url=/";
			
			//Login
			$curl = curl_init($login_url); 
    		curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
			$this->data = curl_exec($curl);
			
			//Get Source
			$curl = curl_init("http://poj.org/showsource?solution_id=".$this->res['rid']); 
    		curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
			$src = curl_exec($curl);
			@unlink($cookie_file);
			$th = new HTMLParser();
			$th->loadHTML($src);
			$this->res['memory'] = $th->innerHTML('<td><b>Memory:</b> ', '</td>');
			$this->res['long'] = $th->innerHTML('<td><b>Time:</b> ', '</td>');
			$this->res['lang'] = $th->innerHTML('<td><b>Language:</b> ', '</td>');
			$th->loadHTML($th->startString('<td><b>Result:</b> '));
			$th->loadHTML($th->startString('<font '));
			$this->res['result'] = $th->innerHTML('>', '</font>');
			if ($this->res['memory'] == "N/A")
				$this->res['memory'] = "0K";
			if ($this->res['long'] == "N/A")
				$this->res['long'] = "0MS";
			$this->db->set(array(
					'memory' => $this->res['memory'],
					'long' => $this->res['long'],
					'lang' => $this->res['lang'],
					'result' => $this->res['result']
				))->where("`id` = '".$this->id."'")->update("Record");
			return $this->res;
		}

		private function getRunID()
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
	
?>
