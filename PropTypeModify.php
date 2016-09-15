<?php
	ob_start();
	session_start();
	
	if (isset($_GET["ptypeid"]) && isset($_GET["Action"]))
	{
		$_SESSION["ptypeid"] = $_GET["ptypeid"];
		$_SESSION["Action"] = $_GET["Action"];
	}

	$ptypeid = $_SESSION["ptypeid"];
	$Action = $_SESSION["Action"];
?>

<html>
<body>
	<?php
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
	
	$query="SELECT * FROM PropertyType WHERE ptype_id = ".$ptypeid;
	$stmt = oci_parse($conn,$query);
	oci_execute($stmt);
	$row = oci_fetch_array($stmt);
	
	include 'functions.php';
	if (login("PropTypeModify.php"))
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
	?>
</body>
</html>



<?php
	function Update()
	{
		global $ptypeid;
		global $row;
		
		?>
		<form method = "post" action = "PropTypeModify.php?ptypeid=<?php echo $ptypeid;?>&Action=ConfirmUpdate">
			<center>Update<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>Property Type ID</b></td>
					<td><?php echo $row["PTYPE_ID"];?></td>
				</tr>
				
				<tr>
					<td><b>Property Type Name</b></td>
					<td><input type="text" name="ptypename" size="30" value="<?php echo $row["PTYPE_NAME"]; ?>"></td>
				</tr>
				
			</table> <br/>

			<table align="center">
				<tr>
					<td><input type = "submit" value = "Update Type"></td>
					<td><input type = "button" value = "Return to List" onclick="window.location.href='PropTypeModify.php';"/></td>
				</tr>
			</table>
		</form>

		<?php
	}
	
	function ConfirmUpdate()
	{
		global $conn;
		global $ptypeid;
		
		$query="UPDATE PropertyType SET ptype_name = '$_POST[ptypename]'
		WHERE ptype_id = ".$ptypeid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		header("Location: PropType.php");
		exit;
	}
	
	function Del()
	{
		global $ptypeid;
		
		?>
		<center>
		<script language="javascript"> 
			var text =  "Are you sure you want to delete <?php echo $ptypeid;?>?"
			if(window.confirm(text))
			{
				window.location='PropTypeModify.php?ptypeid=<?php echo $ptypeid; ?>&Action=ConfirmDelete';
			}
		</script>
		</center><?php
	}
	
	function COnfirmDel()
	{
		global $conn;
		global $ptypeid;
		
		$query="DELETE FROM PropertyType WHERE ptype_id = ".$ptypeid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		header("Location: PropType.php");
		exit;
	}
?>

