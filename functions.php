<?php
	function login($origin_page)
	{
		if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true)
		{
			return true;
		}
		else
		{
			$_SESSION["origin_page"] = $origin_page;
			header("Location: login.php");
			exit;
		}
	}
?>