<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2015-08-04 13:23:11
        Filename: /home/springhack/sk_vjudge/classes/HTMLParser.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php

	class HTMLParser {
		
		private $html = "", $url = "";
		
		public function HTMLParser($url = "")
		{
			if ($url != "")
				$this->html = file_get_contents($url);
		}
		
		public function loadURL($url = "")
		{
			if ($url != "")
				$this->html = file_get_contents($url);
		}
		
		public function loadHTML($html)
		{
			$this->html = $html;
		}
		
		public function optHTMLLink()
		{
			$str = $this->html;
			while (mb_strstr($str, 'src=') || strstr($str, 'href='))
			{
				if (mb_strstr($str, 'src='))
				{
					$div = mb_substr($str, 4, 1);
					//print_r("src=".$div);
					$url = $this->startString("src=".$div, $div);
					if (!mb_strstr($url, "http://"))
						str_replace($url, "".$url, $this->html);
					$str = mb_substr($str, mb_strlen($url) + 6);
				}
				if (mb_strstr($str, 'href='))
				{
					$div = mb_substr($str, 5, 1);
					$url = $this->startString("href=".$div, $div);
					if (!mb_strstr($url, "http://"))
						str_replace($url, "".$url, $this->html);
					$str = mb_substr($str, mb_strlen($url) + 7);
				}
			}
		}
		
		public function innerHTML($a = "", $b = "", $h = "")
		{
			if (($a == "" && $b == "") || $this->html == "")
				return "";
			if ($h == "")
				$h = $this->html;
			$p1 = mb_strstr($h, $a);
			if (!$p1)
				return "";
			$p1 = mb_substr($p1, strlen($a));
			$p2 = mb_strstr($p1, $b);
			if (!$p2)
				return "";
			return str_replace($p2, "", $p1);
		}
		
		public function startString($a = "")
		{
			if (($a == "" && $b == "") || $this->html == "")
				return "";
			$p1 = mb_strstr($this->html, $a);
			if (!$p1)
				return "";
			return mb_substr($p1, strlen($a));
		}
		
		public function getHTML()
		{
			return $this->html;
		}
		
	}
	
?>
