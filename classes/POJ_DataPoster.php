<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-21 01:39:32
        Filename: POJ_DataPoster.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	class POJ_DataPoster {
		
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
		
		public function POJ_DataPoster($user = "skvj01", $pass = "forskvj", $id = "1000", $lang = "0", $code = "")
		{
			//MySQL
			$this->db = new MySQL();

			//Infomation
			$rid = $_POST['rid'];
			
			//Add record
			$ret = $this->db->value(array(
					'id' => $rid,
					'oid' => $_GET['id'],
					'tid' => $id,
					'rid' => '__',
					'user' => $_SESSION['user'],
					'time' => time(),
					'memory' => 'N/A',
					'long' => 'N/A',
					'lang' => $lang,
					'result' => 'N/A',
					'oj' => 'POJ',
					'oj_u' => $user,
					'oj_p' => $pass,
					'code' => $code
				))->insert("Record");
			$_SESSION['last_id'] = $rid;	
		}
		
		public function getData()
		{
			return $this->data;
		}
		
	}
	
?>
