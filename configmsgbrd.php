<?php
	DEFINE ('DBUSER', 'Nash');
	DEFINE ('DBPW', 'nash');
	DEFINE ('DBHOST', 'localhost');
	DEFINE ('DBNAME', 'msgbrd');
	
	$dbc = new mysqli('localhost', 'root', '', 'msgbrd') or die ("ERROR: No se puede conectar");
	/*if($dbc = mysqli_connect(DBHOST, DBUSER, DBPW)) {
		if(!mysqli_select_db($dbc,DBNAME)) {
			trigger_error("Could not select de database" .mysqli_error());
			exit();
		}
		else {
			trigger_error("Could not connect to mysql");
			exit();
		}
	}*/
	function escope_data ($data) {
		if(function_exists('mysqli_real_escape_string')) {
			global $dbc;
			$data = mysqli_real_escape_string(trim($data), $dbc);
			$data = strip_tags($data);
		}
		else {
			$data = mysqli_escape_string($data);
			$data = strip_tags($data);
		}
		return $data;
	}
?>