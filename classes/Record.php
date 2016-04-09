<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-04-09 19:23:05
        Filename: Record.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	
	class Record {
		
		private $db = NULL;
		private $record = "";
		private $res = NULL;
		
		public function Record($id)
		{
			$this->db = new MySQL();
			$this->res = $this->db->from("Record")->where("`id` = '".$id."'")->select()->fetch_one();
			$str = $this->res['oj']."_Record";
			require_once(dirname(__FILE__).'/'.$str.'.php');
			$this->record = new $str($id);
		}
		
		public function getInfo()
		{
			return $this->record->getInfo();
		}
		
	}
	
?>
