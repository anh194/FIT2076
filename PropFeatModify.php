<?php
	ob_start();
	session_start();
	
	if (isset($_GET["featid"]) && isset($_GET["Action"]))
	{
		$_SESSION["featid"] = $_GET["featid"];
		$_SESSION["Action"] = $_GET["Action"];
	}

	$featid = $_SESSION["featid"];
	$Action = $_SESSION["Action"];
	
	include 'functions.php';
	if (login("PropModify.php"))
	{
		switch ($Action)
		{
			case "Update": 
				Update();
				break;
			case "ConfirmUpdate":
				ConfirmUpdate();
				break;
			case "Delete":
				Del();
				break;
			case "ConfirmDelete":
				ConfirmDel();
				break;
		}
	}
	
	function Update()
	{
		?><h1>UPDATE</h1><?php
	}
	
	function ConfirmUpdate()
	{
		?><h1>CONFIRM UPDATE</h1><?php
	}
	
	function Del()
	{
		?><h1>DELETE</h1><?php
	}
	
	function COnfirmDel()
	{
		?><h1>CONFIRM DELETE</h1><?php
	}
?>