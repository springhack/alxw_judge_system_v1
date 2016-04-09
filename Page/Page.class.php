<?php
	require_once(dirname(__FILE__)."/../App.class.php");
	App::loadMod("User");
	class Page {
		public function __construct()
		{
			global $sql;
			if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'Page'", $sql)) != 1)
			{
				mysql_query("
					CREATE TABLE Page 
					(
						name text,
						title text,
						author text,
						time text,
						json longtext,
						content longtext
					) DEFAULT CHARSET = UTF8; 
				", $sql);
			}
		}
		public function getList($limit = "", $offset = "")
		{
			global $sql;
			$query_str = "SELECT name FROM Page ORDER BY time DESC";
			if ($limit != "")
				$query_str .= " LIMIT ".$limit.", ".$offset;
			$result = mysql_query($query_str, $sql);
			print_r(mysql_error());
			$ret = array();
			while($row = mysql_fetch_array($result))
				$ret[] = $row['name'];
			return $ret;
		}
		public function createPage($name, $title, $author, $content, $json = array(), $time = "")
		{
			global $sql;
			if ($time == "")
				$time = time();
			if (!get_magic_quotes_gpc())
			{
				$name = addslashes($name);
				$title = addslashes($title);
				$author = addslashes($author);
				$content = addslashes($content);
			}
			$json = addslashes(serialize($json));
			mysql_query("INSERT INTO Page
							VALUES ('".$name."', '".$title."', '".$author."', '".$time."', '".$json."', '".$content."')", $sql);
			return $name;
		}
		public function updatePage($name, $title, $author, $content, $json = array(), $time = "")
		{
			global $sql;
			if ($time == "")
				$time = time();
			if (!get_magic_quotes_gpc())
			{
				$name = addslashes($name);
				$title = addslashes($title);
				$author = addslashes($author);
				$content = addslashes($content);
			}
			$json = addslashes(serialize($json));
			mysql_query("UPDATE Page SET
							title = '".$title."', author = '".$author."', time = '".$time."', json = '".$json."', content = '".$content."' WHERE name = '".$name."'", $sql);
			return $name;
		}
		public function deletePage($name)
		{
			global $sql;
			$result = mysql_query("SELECT name FROM Page WHERE name = '".$name."'", $sql);
			while($row = mysql_fetch_array($result))
			{
				mysql_query("DELETE FROM Page WHERE name = '".$name."'", $sql);
				return true;
			}
			return false;
		}
		public function getPage($name)
		{
			global $sql;
			$result = mysql_query("SELECT * FROM Page WHERE name = '".$name."'", $sql);
			while($row = mysql_fetch_array($result))
			{
				$row['json'] = unserialize($row['json']);
				return $row;
			}
			return false;
		}
	}
?>