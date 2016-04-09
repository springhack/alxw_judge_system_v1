<?php /**
        Author: SpringHack - springhack@live.cn
        Last modified: 2016-03-31 17:09:44
        Filename: dMySQL.class.php
        Description: Created by SpringHack using vim automatically.
**/ ?>
<?php
	require_once(dirname(__FILE__)."/../App.class.php");
	class dMySQL {
		private $sql = NULL;
		private $insert_command = array();
		private $update_command = array();
		private $table_struct = array();
		private $query_command = array(
				"from" => "",
				"where" => "",
				"order" => "",
				"limit" => ""
			);
		private $table_change = "";
		private $query_result = "";
		public function __construct($host = false, $user = false, $pass = false, $name = false)
		{
			global $Config, $sql;
			$host = $host?$host:$Config['DB_HOST'];
			$user = $user?$user:$Config['DB_USER'];
			$pass = $pass?$pass:$Config['DB_PASS'];
			$name = $name?$name:$Config['DB_NAME'];
			$this->sql = mysql_connect($host, $user, $pass, true);
			mysql_select_db($name, $this->sql);
		}
		public function close()
		{
			return mysql_close($this->sql);
		}
		public function num_rows()
		{
			return mysql_num_rows($this->query_result);
		}
		public function error()
		{
			return mysql_error($this->sql);
		}
		public function from($table)
		{
			$this->query_command['from'] = "FROM `".$table."`";
			return $this;
		}
		public function where($where = "1 = 1")
		{
			$this->query_command['where'] = "WHERE ".$where;
			return $this;
		}
		public function order($order = "DESC", $key = "id")
		{
			$this->query_command['order'] = "ORDER BY ".$key." ".$order;
			return $this;
		}
		public function limit($limit = "100", $offset = "0")
		{
			$this->query_command['limit'] = "LIMIT ".$offset.", ".$limit;
			return $this;
		}
		public function select($select = "*")
		{
			$this->query_result = mysql_query("SELECT ".$select." ".implode(" ", $this->query_command), $this->sql);
			$this->query_command = array(
					"from" => "",
					"where" => "",
					"order" => "",
					"limit" => ""
				);
			return $this;
		}
		public function delete($delete = "")
		{
			$this->query_result = mysql_query("DELETE ".implode(" ", $this->query_command), $this->sql);
			$this->query_command = array(
					"from" => "",
					"where" => "",
					"order" => "",
					"limit" => ""
				);
			return $this;
		}
		public function query($cmd)
		{
			$this->query_result = mysql_query($cmd, $this->sql);
			return $this;
		}
		public function fetch_one()
		{
			$tmp = $this->fetch_all();
			return isset($tmp[0])?$tmp[0]:"";
		}
		public function fetch_all()
		{
			$ret = array();
			while($row = mysql_fetch_array($this->query_result))
				$ret[] = $row;
			return $ret;
		}
		public function value($arr)
		{
			foreach ($arr as $key => $val)
				$this->insert_command[$key] = $val; 
			return $this;
		}
		public function insert($table)
		{
			$str = "INSERT INTO `".$table."` (";
			$tmp_arr = array();
			foreach ($this->insert_command as $key => $val)
				$tmp_arr[] = "`".$key."`";
			$str .= implode(", ", $tmp_arr).") VALUES (";
			$tmp_arr = array();
			foreach ($this->insert_command as $key => $val)
				$tmp_arr[] = "'".$val."'";
			$str .= implode(", ", $tmp_arr).")";
			$this->query_result = mysql_query($str, $this->sql);
			return $this;
		}
		public function struct($arr)
		{
			foreach ($arr as $key => $val)
				$this->table_struct[$key] = $val; 
			return $this;
		}
		public function create($table)
		{
			$str = "CREATE TABLE `".$table."` (";
			$tmp_arr = array();
			foreach ($this->table_struct as $key => $val)
				$tmp_arr[] = "`".$key."` ".$val;
			$str .= implode(", ", $tmp_arr).") DEFAULT CHARSET = UTF8;";
			$this->query_result = mysql_query($str, $this->sql);
			return $this;
		}
		public function set($arr)
		{
			foreach ($arr as $key => $val)
				$this->update_command[$key] = $val; 
			return $this;
		}
		public function update($table)
		{
			$str = "UPDATE `".$table."` SET ";
			$tmp_arr = array();
			foreach ($this->update_command as $key => $val)
				$tmp_arr[] = "`".$key."` = '".$val."'";
			$str .= implode(", ", $tmp_arr).$this->query_command['where'];
			$this->query_result = mysql_query($str, $this->sql);
			$this->query_command = array(
					"from" => "",
					"where" => "",
					"order" => "",
					"limit" => ""
				);
			return $this;
		}
		public function table($table)
		{
			$this->table_change = $table;
			return $this;
		}
		public function add($key, $type = "longtext")
		{
			$str = "alter table ".$this->table_change." add ".$key." ".$type.";";
			$this->query_result = mysql_query($str, $this->sql);
			return $this;
		}
		public function modify($o_key, $n_key, $type = "longtext")
		{
			$str = "alter table ".$this->table_change." change ".$o_key." ".$n_key." ".$type.";";
			$this->query_result = mysql_query($str, $this->sql);;
			return $this;
		}
		public function drop($key)
		{
			$str = "alter table ".$this->table_change." drop column ".$key.";";
			$this->query_result = mysql_query($str, $this->sql);
			return $this;
		}
		public function ping()
		{
			return mysql_ping($this->sql);
		}
	}
?>
