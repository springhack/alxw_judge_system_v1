<?php
	require_once(dirname(__FILE__)."/../App.class.php");
	App::loadMod("User");
	class Eassy {
		public function __construct()
		{
			global $sql;
			if(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'Eassy'", $sql)) != 1)
			{
				mysql_query("
					CREATE TABLE Eassy 
					(
						tid text,
						title text,
						author text,
						time text,
						type text,
						json longtext,
						content longtext
					) DEFAULT CHARSET = UTF8; 
				", $sql);
			}
		}
		public function getList($type = false, $limit = "100", $offset = "0", $user = "")
		{
			global $sql;
			$query_str = "SELECT tid FROM Eassy";
			if ($type == false)
				$query_str .= " WHERE type <> '草稿'";
			if ($type != false && $type != 1)
				$query_str .= " WHERE type = '".$type."'";
			if ($type == 1)
			{
				if ($user != "")
					$query_str .= " WHERE author = '".$user."'";
			} else {
				if ($user != "")
					$query_str .= " AND author = '".$user."'";
			}
			$query_str .= " ORDER BY time DESC";
			if ($limit != "")
				$query_str .= " LIMIT ".$offset.", ".$limit;
			$result = mysql_query($query_str, $sql);
			$ret = array();
			while($row = mysql_fetch_array($result))
				$ret[] = $row['tid'];
			return $ret;
		}
		public function createEassy($title, $author, $type, $content, $json = array(), $time = "")
		{
			global $sql;
			if ($time == "")
				$time = time();
			$tid = uniqid();
			if (!get_magic_quotes_gpc())
			{
				$title = addslashes($title);
				$author = addslashes($author);
				$type = addslashes($type);
				$content = addslashes($content);
			}
			$json = addslashes(serialize($json));
			mysql_query("INSERT INTO Eassy
							VALUES ('".$tid."', '".$title."', '".$author."', '".$time."', '".$type."', '".$json."', '".$content."')", $sql);
			return $tid;
		}
		public function updateEassy($tid, $title, $author, $type, $content, $json = array(), $time = "")
		{
			global $sql;
			if ($time == "")
				$time = time();
			if (!get_magic_quotes_gpc())
			{
				$title = addslashes($title);
				$author = addslashes($author);
				$type = addslashes($type);
				$content = addslashes($content);
			}
			$json = addslashes(serialize($json));
			mysql_query("UPDATE Eassy SET
							title = '".$title."', author = '".$author."', time = '".$time."', type = '".$type."', json = '".$json."', content = '".$content."' WHERE tid = '".$tid."'", $sql);
			return $tid;
		}
		public function deleteEassy($tid)
		{
			global $sql;
			$result = mysql_query("SELECT tid FROM Eassy WHERE tid = '".$tid."'", $sql);
			while($row = mysql_fetch_array($result))
			{
				mysql_query("DELETE FROM Eassy WHERE tid = '".$tid."'", $sql);
				mysql_query("DELETE FROM Talk WHERE tid = '".$tid."'", $sql);
				return true;
			}
			return false;
		}
		public function getEassy($tid)
		{
			global $sql;
			$result = mysql_query("SELECT * FROM Eassy WHERE tid = '".$tid."'", $sql);
			while($row = mysql_fetch_array($result))
			{
				$row['json'] = unserialize($row['json']);
				return $row;
			}
			return false;
		}
	}
?>