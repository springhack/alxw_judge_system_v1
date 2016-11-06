<?php

	function mysql_connect($a, $b, $c, $d = false)
	{
		return mysqli_connect($a, $b, $c);
	}

	function mysql_select_db($db, &$sql)
	{
		return mysqli_select_db($sql, $db);
	}

	function mysql_close(&$sql)
	{
		return mysqli_close($sql);
	}

	function mysql_num_rows($res)
	{
		return mysqli_num_rows($res);
	}

	function mysql_error(&$sql)
	{
		return mysqli_error($sql);
	}

	function mysql_query($q, &$s)
	{
		return mysqli_query($s, $q);
	}

	function mysql_fetch_array($res)
	{
		return mysqli_fetch_array($res);
	}

	function mysql_ping(&$sql)
	{
		return mysqli_ping($sql);
	}

    function mysql_insert_id(&$sql)
    {
        return mysqli_insert_id($sql);
    }

?>
