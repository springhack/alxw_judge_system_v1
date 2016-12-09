<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-05-02 23:04:54
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


		public function POJ_DataPoster($user = "skvj01", $pass = "forskvj", $id = "1000", $lang = "0", $code = "", $cid = '0')
		{
			//MySQL
			$this->db = new MySQL();
			
			//Add record
			$ret = $this->db->value(array(
					'oid' => $_GET['id'],
					'tid' => $id,
					'rid' => '1234',
					'user' => $_SESSION['user'],
					'nick' => $_SESSION['nick'],
					'time' => time(),
					'memory' => 'N/A',
					'long' => 'N/A',
					'lang' => $lang,
					'result' => 'N/A',
					'oj' => 'POJ',
					'oj_u' => $user,
					'oj_p' => $pass,
					'code' => 'This is a joke',
					'contest' => $cid
				))->insert("Record");

            //Get LocalID
			$_SESSION['last_id'] = $this->db->mysql_insert_id();

            //Encode code
            $_code = $this->code_encode('//<ID>'.$_SESSION['last_id'].'</ID>'.PHP_EOL.$code);

            //Update code and set can judge
            $this->db->set(array(
                'code' => $_code,
                'rid' => '__'
            ))->where('`id`='.$_SESSION['last_id'])->update('Record');
		}
		
		public function getData()
		{
			return $this->data;
		}

        public function code_encode($string)
        {
            $string = preg_replace('/\r\n/', "\n", $string);
            $input = '';
            for ($i=0;$i<strlen($string);++$i)
            {
                if (ord($string[$i]) > 127)
                {
                    $i += 2;
                    $input .= '_';
                } else
                    $input .= $string[$i];
            }
            return base64_encode($input);
        }
		
	}
	
?>
