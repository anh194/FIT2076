<?php
	ob_start();
	session_start();
	
	if (isset($_GET["propid"]))
	{
		$_SESSION["propid"] = $_GET["propid"];
	}
	
	if (isset($_GET["Action"]))
	{
		$_SESSION["Action"] = $_GET["Action"];
	}

	if (isset ($_SESSION["propid"]))
	{
		$propid = $_SESSION["propid"];
	}
	
	if (isset ($_SESSION["Action"]))
	{
		$Action = $_SESSION["Action"];
	}
	
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
?>
	
<html>
<body>
	<?php
	if (isset($propid))
	{
		$query="SELECT * FROM Property WHERE PROP_ID = ".$propid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		$row = oci_fetch_array($stmt);
	}

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
			case "Create":
				Create();
				break;
			case "ConfirmCreate":
				ConfirmCreate();
				break;
			case "View":
				View();
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
			<center>Update Property<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>ID</b></td>
					<td><?php echo $row["PROP_ID"];?></td>
				</tr>
				
				<tr>
					<td><b>Address</b></td>
					<td><input type="text" name="propaddress" size="30" value="<?php echo $row["PROP_STREET"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Country</b></td>
					<td><input type="text" name="propcountry" size="30" value="<?php echo $row["PROP_COUNTRY"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>State</b></td>
					<td><input type="text" name="propstate" size="30" value="<?php echo $row["PROP_STATE"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>City</b></td>
					<td><input type="text" name="propcity" size="30" value="<?php echo $row["PROP_CITY"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Postcode</b></td>
					<td><input type="text" name="proppostcode" size="30" value="<?php echo $row["PROP_POSTCODE"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Property Type</b></td>
					<td>
						<?php
						$query = "SELECT * FROM PropertyType ORDER BY ptype_name";
						$stmt = oci_parse($conn,$query);
						oci_execute($stmt);
						?>
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
							oci_free_statement($stmt);
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td><b>Bedrooms</b></td>
					<td>
						<select name="propbedrooms">
							<?php
							for ($i=1; $i < 10; $i++)
							{
								?>
								<option value="<?php echo $i;?>" 
									<?php if ($row["PROP_BEDROOMS"] == $i) echo 'selected';?>>
									<?php echo $i.' beds'; ?>
								</option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td><b>Bathrooms</b></td>
					<td>
						<select name="propbathrooms">
							<?php
							for ($i=1; $i < 10; $i++)
							{
								?>
								<option value="<?php echo $i;?>" 
									<?php if ($row["PROP_BATHROOMS"] == $i) echo 'selected';?>>
									<?php echo $i.' bathrooms'; ?>
								</option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>

				<tr>
					<td><b>Property Description</b></td>
					<td><textarea type="text" name="propdescription" size="30"><?php echo $row["PROP_DESCRIPTION"]; ?></textarea></td>
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
		
		$query="UPDATE Property set 
			PROP_STREET = '$_POST[propaddress]',
			PROP_COUNTRY = '$_POST[propcountry]',
			PROP_STATE = '$_POST[propstate]',
			PROP_CITY = '$_POST[propcity]',
			PROP_POSTCODE = '$_POST[proppostcode]',
			PROP_TYPE = '$_POST[PropertyTypeList]',
			PROP_BEDROOMS = '$_POST[propbedrooms]',
			PROP_BATHROOMS = '$_POST[propbathrooms]',
			PROP_DESCRIPTION = '$_POST[propdescription]'
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
	
	function Create()
	{
		global $conn;
		?>
		<form method = "post" action = "PropModify.php?&Action=ConfirmCreate">
			<center>New Property<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>Address</b></td>
					<td><input type="text" name="propstreet" size="30"></td>
				</tr>
			
				<tr>
					<td><b>Country</b></td>
					<td><input type="text" name="propcountry" size="30"></td>
				</tr>
				
				<tr>
					<td><b>State</b></td>
					<td><input type="text" name="propstate" size="30"></td>
				</tr>
				
				<tr>
					<td><b>City</b></td>
					<td><input type="text" name="propcity" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Postcode</b></td>
					<td><input type="text" name="proppostcode" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Type</b></td>
					<td>
						<?php
						$query = "SELECT * FROM PropertyType ORDER BY ptype_name";
						$stmt = oci_parse($conn,$query);
						oci_execute($stmt);
						?>
						
						<form>
							<select name="proptype">
								<?php
								while ($ptypeRow = oci_fetch_array($stmt))
								{
									?>		
									<option value="<?php echo $ptypeRow["PTYPE_ID"]; ?>">
									<?php echo $ptypeRow["PTYPE_NAME"];?>
									</option>
									<?php
								}
								?>
							</select>
						</form>
					</td>
				</tr>

				<tr>
					<td><b>Bedrooms</b></td>
					<td><input type="text" name="propbedrooms" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Bathrooms</b></td>
					<td><input type="text" name="propbathrooms" size="30"></td>
				</tr>

				<tr>
					<td><b>Description</b></td>
					<td><input type="text" name="propdescription" size="30"></td>
				</tr>
				
				
			</table> <br/>

			<table align="center">
				<tr>
				<td><input type = "submit" value = "Create Property"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='Property.php';"/></td>
				</tr>
			</table>
		</form>
		<?php
	}
	
	function ConfirmCreate()
	{
		global $conn;
		$query = "INSERT INTO Property VALUES (
			prop_seq.nextval,
			'$_POST[propcountry]',
			'$_POST[propstate]',
			'$_POST[propcity]',
			'$_POST[proppostcode]',
			'$_POST[propstreet]',
			'$_POST[proptype]',
			'$_POST[propbedrooms]',
			'$_POST[propbathrooms]',
			'$_POST[propdescription]')";
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);
		
		header("Location: Property.php");
		exit;
	}
	
	function View()
	{
		global $conn;
		global $propid;
		global $row;
		?>
		<center>View Property<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>ID</b></td>
					<td><?php echo $row["PROP_ID"];?></td>
				</tr>
				
				<tr>
					<td><b>Address</b></td>
					<td><?php echo $row["PROP_STREET"]; ?></td>
				</tr>
				
				<tr>
					<td><b>Country</b></td>
					<td><?php echo $row["PROP_COUNTRY"]; ?></td>
				</tr>
				
				<tr>
					<td><b>State</b></td>
					<td><?php echo $row["PROP_STATE"]; ?></td>
				</tr>
				
				<tr>
					<td><b>City</b></td>
					<td><?php echo $row["PROP_CITY"]; ?></td>
				</tr>
				
				<tr>
					<td><b>Postcode</b></td>
					<td><?php echo $row["PROP_POSTCODE"]; ?></td>
				</tr>
				
				<tr>
					<td><b>Type</b></td>
					<td><?php echo $row["PROP_TYPE"]; ?></td>
				</tr>
				
				<tr>
					<td><b>Bedrooms</b></td>
					<td><?php echo $row["PROP_BEDROOMS"]; ?></td>
				</tr>
				
				<tr>
					<td><b>Bathrooms</b></td>
					<td><?php echo $row["PROP_BATHROOMS"]; ?></td>
				</tr>

				<tr>
					<td><b>Description</b></td>
					<td><?php echo $row["PROP_DESCRIPTION"]; ?></td>
				</tr>

			</table> <br/>

			<table align="center">
				<tr>
				<td><input type = "button" value = "Edit Property" onclick="window.location.href='Property.php?prop_id='"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='Property.php';"/></td>
				</tr>
			</table>
		<?php
	}
?>
