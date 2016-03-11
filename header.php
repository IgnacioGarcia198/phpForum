<?php
	ob_start();
	session_start();
	require_once('configmsgbrd.php');
	require_once('recaptchalib.php');
?>

<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title>Playing with Layouts</title>
<style>
	body {
		background-color: #FFF;
	}
	#header {
		background-color: #006;
		color: #FFF;
		padding: 20px;
	}
	#footer {
		background-color: #006;
		color: #FFF;
		padding: 20px;
	}
	#main {
		padding: 15px;
		background-color: #FAF0E6;
		margin-right: 20px;
		margin-top: 5px;
	}
	#lypsum {
		padding: 15px;
		background-color: #FAF0E6;
		margin-right: 20px;
		margin-top: 5px;
	}
	#login {
		padding: 10px;
		padding-bottom: 15px;
		border: none;
		background-color: #E0FFFF;
		width: 200px;
		text-align: left;
		float: right;
		margin-left: 10px;
		margin-right:5px;
		margin-top: 5px;
	}
</style>
</head>
<body>
</body>
</html>