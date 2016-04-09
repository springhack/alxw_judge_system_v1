<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-21 00:23:23
        Filename: Problem.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	class Problem {
		
		private $pro_info = array();
		private $pro_oj = "POJ_Problem";
		private $problem = NULL;
		private $id = "1000", $oj = "POJ";
		
		public function Problem($id = 1000, $oj = "POJ")
		{
			$this->id = $id;
			$this->oj = $oj;
			require_once(dirname(__FILE__)."/".$oj."_Problem".".php");
			$this->pro_oj = $oj."_Problem";
			$this->problem = new $this->pro_oj($id);
		}
		
		public function getInfo()
		{
			return $this->problem->getInfo();
		}
		
		public function submitCode($lang = "0", $code = "")
		{
			return $this->problem->submitCode($this->oj, $this->id, $lang, $code);
		}
		
		public function getEncodeScript()
		{
			return file_get_contents(dirname(__FILE__)."/../javascript/".$this->oj.".js");
		}
		
	}
	
?>
