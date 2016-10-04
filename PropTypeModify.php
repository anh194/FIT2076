<?php
	ob_start();
	session_start();
	
		if (isset($_GET["ptypeid"]))
	{
		$_SESSION["ptypeid"] = $_GET["ptypeid"];
	}
	
	if (isset($_GET["Action"]))
	{
		$_SESSION["Action"] = $_GET["Action"];
	}
	
	if (isset($_SESSION["ptypeid"]))
	{
		$ptype_id = $_SESSION["ptypeid"];
	}
		
	if (isset($_SESSION["Action"]))
	{
		$Action = $_SESSION["Action"];
	}

?>

<html>
<body>
	<?php
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
	
	if (isset($ptype_id))
	{
		$query="SELECT * FROM PropertyType WHERE ptype_id = ".$ptype_id;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		$row = oci_fetch_array($stmt);
	}
	
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
			case "Create":
				Create();
				break;
			case "ConfirmCreate":
				ConfirmCreate();
				break;
		}
	}
	?>
</body>
</html>



<?php
	function Update()
	{
		global $ptype_id;
		global $row;
		
		?>
		<form method = "post" action = "PropTypeModify.php?ptypeid=<?php echo $ptype_id;?>&Action=ConfirmUpdate">
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
					<td><input type = "submit" value = "Confirm Update" onclick = window.location.href='PropTypeModify.php?ptypeid=<?php echo $ptype_id;?>&Action=ConfirmDelete'></td>
					<td><input type = "button" value = "Return to List" onclick="window.location.href='PropType.php';"/></td>
				</tr>
			</table>
		</form>

		<?php
	}
	
	function ConfirmUpdate()
	{
		global $conn;
		global $ptype_id;
		
		$query="UPDATE PropertyType SET ptype_name = '$_POST[ptypename]'
		WHERE ptype_id = ".$ptype_id;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		?>
		<script language="javascript"> 
			window.alert("Type successfully updated!");
			window.location = 'PropType.php';
		</script>
		<?php
	}
	
	function Del()
	{
		global $row;
		global $ptype_id;
		?>
		<center>
		<!--
		<script language="javascript"> 
			var text =  "Are you sure you want to delete <?php //echo $ptype_id;?>?"
			if(window.confirm(text))
			{
				window.location='PropTypeModify.php?ptypeid=<?php //echo $ptype_id; ?>&Action=ConfirmDelete';
			}
		</script>
		-->
		
			<b>Are you sure you want to delete the following record?</b>
			<table align ="center" cellpadding="3">
				<tr> 
					<td><b>ID</b></td>
					<td><?php echo $ptype_id;?></td>
				</tr>
				<tr>
					<td><b>Type</b></td>
					<td><?php echo $row["PTYPE_NAME"];?></td>
				</tr>
			</table>
			
			<table align="center">
				<tr>
					<td><input type = "button" value = "Confirm Delete" onclick="window.location.href='PropTypeModify.php?ptypeid=<?php echo $ptype_id; ?>&Action=ConfirmDelete';"></td>
					<td><input type = "button" value = "Return to List" onclick="window.location.href='PropType.php';"/></td>
				</tr>
			</table>
		
		</center>
		<?php
	}
	
	function ConfirmDel()
	{
		global $conn;
		global $ptype_id;
		
		$query="DELETE FROM PropertyType WHERE ptype_id = ".$ptype_id;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);
		?>
		<script language="javascript"> 
			window.alert("Delete was successful!");
			window.location = 'PropType.php';
		</script>
		<?php
	}
	
	function Create()
	{
		?>
		<form method = "post" action = "PropTypeModify.php?Action=ConfirmCreate">
			<center>New Property Type<br/></center>
			
			<table align ="center" cellpadding="3">
				
				<tr>
					<td><b>Property Type Name</b></td>
					<td><input type="text" name="ptypename" size="30"></td>
				</tr>
				
			</table> <br/>

			<table align="center">
				<tr>
					<td><input type = "submit" value = "Create Type"></td>
					<td><input type = "button" value = "Return to List" onclick="window.location.href='PropType.php';"/></td>
				</tr>
			</table>
		</form>

		<?php
	}
	
	function ConfirmCreate()
	{
		global $conn;
		$query="INSERT INTO PropertyType VALUES (ptype_seq.nextval,'$_POST[ptypename]')";
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		?>
		<script language="javascript"> 
			window.alert("Type successfully created!");
			window.location = 'PropType.php';
		</script>
		<?php
	}
?>

