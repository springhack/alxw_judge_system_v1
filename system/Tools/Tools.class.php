<?php
	require_once(dirname(__FILE__)."/../App.class.php");
	App::loadMod("Setting");
	class Tools {
		private $setting;
		
		public function __construct()
		{
			$this->setting = new Setting();
		}
		public function dealString($str = "")
		{
			if (!get_magic_quotes_gpc())
				return addslashes($str);
			return $str;
		}
		public function dealSiteOpen()
		{
			if ($this->setting->get("SiteOpen", "on") == "off")
			{
				die($this->setting->get("CloseReason", file_get_contents(dirname(__FILE__)."/../admin/error.php")));
			}
		}
		public function dealEncode($data)
		{
			if (!empty($data))
			{
				$fileType = mb_detect_encoding($data, array(
						'UTF-8',
						'GBK',
						'LATIN1',
						'BIG5',
						'GB2312'
					));
				if ($fileType != 'UTF-8')
					$data = mb_convert_encoding($data, 'UTF-8', $fileType);
			}
			return $data;
		}
		public function firstImg($str, $all = false)
		{
			$start = strstr($str, "<img");
			if ($start == false)
				return "";
			$str_a = explode(">", $start);
			if (count($str_a) <= 0)
				return "";
			if ($all)
				return $str_a[0].">";
			else {
				$str_b = strstr($str_a[0], "src=");
				$str_c = explode(substr($str_b, 4, 1), $str_b);
				return isset($str_c[1])?$str_c[1]:"";
			}
		}
		public function cutString($str, $len = 50)
		{
			return mb_substr(strip_tags($str), 0, $len, 'utf-8');
		}
	}
?>