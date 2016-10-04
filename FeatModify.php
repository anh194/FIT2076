<?php
	ob_start();
	session_start();
	include 'functions.php';
	
	if (isset($_GET["featid"]))
	{
		$_SESSION["featid"] = $_GET["featid"];
	}
	
	if (isset($_GET["Action"]))
	{
		$_SESSION["Action"] = $_GET["Action"];
	}
	
	if (isset($_SESSION["featid"]))
	{
		$featid = $_SESSION["featid"];
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
	
	if (isset($featid))
	{
		$query="SELECT * FROM Feature WHERE feat_id = ".$featid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		$row = oci_fetch_array($stmt);
	}

	if (login("FeatModify.php"))
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
		global $featid;
		global $row;
		
		?>
		<form method = "post" action = "FeatModify.php?featid=<?php echo $featid;?>&Action=ConfirmUpdate">
			<center>Update<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>Feature ID</b></td>
					<td><?php echo $row["FEAT_ID"];?></td>
				</tr>
				
				<tr>
					<td><b>Feature Name</b></td>
					<td><input type="text" name="featname" size="30" value="<?php echo $row["FEAT_NAME"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Feature Description</b></td>
					<td><input type="text" name="featdescription" size="30" value="<?php echo $row["FEAT_DESCRIPTION"]; ?>"></td>
				</tr>
				
			</table> <br/>

			<table align="center">
				<tr>
				<td><input type = "submit" value = "Update Feature"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='Feature.php';"/></td>
				</tr>
			</table>
		</form>

		<?php
	}
	
	function ConfirmUpdate()
	{
		global $conn;
		global $featid;
		
		$query="UPDATE Feature SET feat_name = '$_POST[featname]',
		feat_description = '$_POST[featdescription]'
		WHERE feat_id = ".$featid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		header("Location: Feature.php");
		exit;
	}
	
	function Del()
	{
		global $featid;
		
		?>
		<center>
		<script language="javascript"> 
			var text =  "Are you sure you want to delete <?php echo $featid;?>?"
			if(window.confirm(text))
			{
				window.location='FeatModify.php?featid=<?php echo $featid; ?>&Action=ConfirmDelete';
			}
		</script>
		</center><?php
	}
	
	function ConfirmDel()
	{
		global $conn;
		global $featid;
		
		$query="DELETE FROM Feature WHERE feat_id = ".$featid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		header("Location: Feature.php");
		exit;
	}
	
	function Create()
	{
		?>
		<form method = "post" action = "FeatModify.php?&Action=ConfirmCreate">
			<center>New Feature<br/></center>
			
			<table align ="center" cellpadding="3">
				
				<tr>
					<td><b>Feature Name</b></td>
					<td><input type="text" name="featname" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Feature Description</b></td>
					<td><input type="text" name="featdescription" size="30"></td>
				</tr>
				
			</table> <br/>

			<table align="center">
				<tr>
				<td><input type = "submit" value = "Create Feature"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='Feature.php';"/></td>
				</tr>
			</table>
		</form>
		<?php
	}
	
	function ConfirmCreate()
	{
		global $conn;
		$query = "INSERT INTO Feature VALUES (feat_seq.nextval,'$_POST[featname]','$_POST[featdescription]')";
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);
		
		header("Location: Feature.php");
		exit;
	}
?>