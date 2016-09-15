<?php
	ob_start();
	session_start();
	
	if (isset($_GET["propid"]) && isset($_GET["Action"]))
	{
		$_SESSION["propid"] = $_GET["propid"];
		$_SESSION["Action"] = $_GET["Action"];
	}

	$propid = $_SESSION["propid"];
	$Action = $_SESSION["Action"];
?>
	
<html>
<body>
	<?php
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
	
	$query="SELECT * FROM Property WHERE PROP_ID = ".$propid;
	$stmt = oci_parse($conn,$query);
	oci_execute($stmt);
	$row = oci_fetch_array($stmt);

	include("functions.php");
	if (login("PropModify.php"))
	{
		switch ($Action)
		{
			case "Update": 
				Update($row);
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

	oci_close($conn);
	?>
</body>
</html>

<!-- FUNCTIONS -->

<?php
	function Update()
	{
		global $conn;
		global $propid;
		global $row;
		
		?>
		<form method = "post" action = "PropModify.php?propid=<?php echo $propid;?>&Action=ConfirmUpdate">
			<center>Update<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>Property ID</b></td>
					<td><?php echo $row["PROP_ID"];?></td>
				</tr>
				
				<tr>
					<td><b>Property Address</b></td>
					<td><input type="text" name="propaddress" size="30" value="<?php echo $row["PROP_STREET"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Property Description</b></td>
					<td><input type="text" name="propdescription" size="30" value="<?php echo $row["PROP_DESCRIPTION"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Property Type</b></td>
				<td>
				
				<?php
				$query = "SELECT * FROM PropertyType ORDER BY ptype_name";
				
				$stmt = oci_parse($conn,$query);
				oci_execute($stmt);
				?>
				
				<form>
					<select name="PropertyTypeList">
					<?php
					while ($ptypeRow = oci_fetch_array($stmt))
					{
						?>		
						<option value="<?php echo $ptypeRow["PTYPE_ID"]; ?>"
						<?php if ($row["PROP_TYPE"] == $ptypeRow["PTYPE_ID"]) echo 'selected';?>>
						<?php echo $ptypeRow["PTYPE_NAME"];?>
						</option>
						<?php
					}
					?>
					</select>
				</form>
			
				</td>
				</tr>
			</table> <br/>

			<table align="center">
				<tr>
				<td><input type = "submit" value = "Update Property"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='Property.php';"/></td>
				</tr>
			</table>
		</form>

		<?php
	}

	function ConfirmUpdate()
	{
		global $conn;
		global $propid;
		
		$query="UPDATE Property set PROP_STREET = '$_POST[propaddress]',
		PROP_DESCRIPTION = '$_POST[propdescription]',
		PROP_TYPE = '$_POST[PropertyTypeList]'
		WHERE prop_id = ".$propid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		header("Location: Property.php");
		exit;
	}

	function Del()
	{
		global $propid;
		
		?>
		<center>
		<script language="javascript"> 
			var text =  "Are you sure you want to delete <?php echo $propid;?>?"
			if(window.confirm(text))
			{
				window.location='PropModify.php?propid=<?php echo $propid; ?>&Action=ConfirmDelete';
			}
		</script>
		</center><?php
	}

	function ConfirmDel()
	{
		global $conn;
		global $propid;
		
		$query="DELETE FROM Property WHERE prop_id = ".$propid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		header("Location: Property.php");
		exit;
	}
?>
