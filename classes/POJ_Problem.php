<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-01-31 03:07:51
        Filename: POJ_Problem.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	require_once(dirname(__FILE__)."/DataPoster.php");

	class POJ_Problem {
		
		private $pro_info = array();
		private $pro_submit = NULL;
		
		public function POJ_Problem($id = 1000)
		{
			require_once(dirname(__FILE__)."/HTMLParser.php");
			$html = new HTMLParser("http://poj.org/problem?id=".$id);
			$html->optHTMLLink();
			$pro_info = array(
					'title' => $html->innerHTML('<div class="ptt" lang="en-US">', '</div>')
				);
			$prefix = $html->startString('<div class="ptt" lang="en-US">'.$pro_info['title']);
			$pro_info['time'] = intval($html->innerHTML('<div class="plm"><table align="center"><tr><td><b>Time Limit:</b> ', 'MS</td>', $prefix));
			$pro_info['memory'] = intval($html->innerHTML('MS</td><td width="10px"></td><td><b>Memory Limit:</b> ', 'K</td>'));
			$pro_info['submissions'] = intval($html->innerHTML('Total Submissions:</b> ', '</td>'));
			$pro_info['accepted'] = intval($html->innerHTML('</td><td><b>Accepted:</b> ', '</td>'));
			$pro_info['description'] = $html->innerHTML('<p class="pst">Description</p><div class="ptx" lang="en-US">', '</div>');
			$pro_info['input'] = $html->innerHTML('<p class="pst">Input</p><div class="ptx" lang="en-US">', '</div>');
			$pro_info['output'] = $html->innerHTML('<p class="pst">Output</p><div class="ptx" lang="en-US">', '</div>');
			$pro_info['sample_input'] = $html->innerHTML('<p class="pst">Sample Input</p><pre class="sio">', '</pre>');
			$pro_info['sample_output'] = $html->innerHTML('<p class="pst">Sample Output</p><pre class="sio">', '</pre>');
			$pro_info['hint'] = $html->innerHTML('<p class="pst">Hint</p><div class="ptx" lang="en-US">', '</div><p class="pst">Source</p>');
			$pro_info['source'] = $html->innerHTML('<p class="pst">Source</p><div class="ptx" lang="en-US">', '</div>');
			$this->pro_info = $pro_info;
		}
		
		public function getInfo()
		{
			return $this->pro_info;
		}
		
		public function submitCode($oj = "POJ", $id = "1000", $lang = "0", $code = "")
		{
			$this->pro_submit = new DataPoster($oj, $id, $lang, $code);
		}
		
	}
	
?>
