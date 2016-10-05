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
				showPropertyForm();
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
				showPropertyForm();
				break;
			case "ConfirmCreate":
				ConfirmCreate();
				break;
		}
	}
	oci_close($conn);
	?>
</body>
</html>

<!-- FUNCTIONS -->

<?php	

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
		
		//delete any existing features
		$query = "DELETE FROM PropertyFeature WHERE pf_property = ".$propid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);

		//for each checked feature
		foreach($_POST['feats'] as $feature)
		{
			//add property feature
			$query = "INSERT INTO PropertyFeature VALUES (".$propid.",".$feature.")";
			$stmt = oci_parse($conn,$query);
			oci_execute($stmt);	
			oci_free_statement($stmt);
		}

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
	
	function showPropertyForm()
	{
		global $conn;
		global $propid;
		global $row;
		global $Action;
		
		if ($Action == "Update")
		{
			$nextAction = "ConfirmUpdate";
			$propstreet = $row["PROP_STREET"];
			$propcountry = $row["PROP_COUNTRY"];
			$propstate = $row["PROP_STATE"];
			$propcity = $row["PROP_CITY"];
			$proppostcode = $row["PROP_POSTCODE"];
			$proptype = $row["PROP_TYPE"];
			$propbedrooms = $row["PROP_BEDROOMS"];
			$propbathrooms = $row["PROP_BATHROOMS"];
			$propdescription = $row["PROP_DESCRIPTION"];
		}
		else if ($Action == "Create")
		{
			$nextAction = "ConfirmCreate";
			$propstreet = "";
			$propcountry = "";
			$propstate = "";
			$propcity = "";
			$proppostcode = "";
			$proptype = "";
			$propbedrooms = "";
			$propbathrooms = "";
			$propdescription = "";
		}
		
		?>
		<form method = "post" action = "PropModify.php?propid=<?php echo $propid;?>&Action=<?php echo $nextAction; ?>">
			<h1>Property </h1>
			<h2>Modify</h2>
			
			<table>
				<tr>
				<td><input type = "submit" value = "<?php echo $Action; ?>"></td>
				<td><input type = "button" value = "Delete" onclick="window.location.href='PropModify.php?propid=<?php echo $propid; ?>&Action=Delete';"/></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='Property.php';"/></td>
				</tr>
			</table> <br/>
			
			<table cellpadding="3">
			
				<tr>
					<td><b>ID</b></td>
					<td><?php echo $propid;?></td>
				</tr>
				
				<tr>
					<td><b>Address</b></td>
					<td><input type="text" name="propaddress" size="30" value="<?php echo $propstreet; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Country</b></td>
					<td><input type="text" name="propcountry" size="30" value="<?php echo $propcountry ?>"></td>
				</tr>
				
				<tr>
					<td><b>State</b></td>
					<td><input type="text" name="propstate" size="30" value="<?php echo $propstate; ?>"></td>
				</tr>
				
				<tr>
					<td><b>City</b></td>
					<td><input type="text" name="propcity" size="30" value="<?php echo $propcity; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Postcode</b></td>
					<td><input type="text" name="proppostcode" size="30" value="<?php echo $proppostcode; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Type</b></td>
					<td><?php showTypeOptions($proptype); ?></td>
				</tr>
				
				<tr>
					<td><b>Bedrooms</b></td>
					<td><?php showBedroomOptions($propbedrooms); ?></td>
				</tr>
				
				<tr>
					<td><b>Bathrooms</b></td>
					<td><?php showBathroomOptions($propbathrooms); ?></td>
				</tr>

				<tr>
					<td><b>Description</b></td>
					<td><textarea type="text" name="propdescription" size="30"><?php echo $propdescription; ?></textarea></td>
				</tr>

			</table> <br/>
			
			<h2>Features</h2>
			
			<?php showPropertyFeatures($propid); ?>
			
			
		</form>
		<?php
	}
	
	function showTypeOptions($proptype)
	{
		global $conn;
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
					<?php if ($proptype == $ptypeRow["PTYPE_ID"]) echo 'selected';?>>
					<?php echo $ptypeRow["PTYPE_NAME"];?>
				</option>
				<?php
			}
			oci_free_statement($stmt);
			?>
		</select>
		<?php
	}
	
	function showBedroomOptions($propbedrooms)
	{
		?>
		<select name="propbedrooms">
			<?php
			for ($i=1; $i < 10; $i++)
			{
				?>
				<option value="<?php echo $i;?>" 
					<?php if ($propbedrooms == $i) echo 'selected';?>>
					<?php echo $i.' beds'; ?>
				</option>
				<?php
			}
			?>
		</select>
		<?php
	}
	
	function showBathroomOptions($propbathrooms)
	{
		?>
		<select name="propbathrooms">
			<?php
			for ($i=1; $i < 10; $i++)
			{
				?>
				<option value="<?php echo $i;?>" 
					<?php if ($propbathrooms == $i) echo 'selected';?>>
					<?php echo $i.' bathrooms'; ?>
				</option>
				<?php
			}
			?>
		</select>
		<?php
	}
	
	function showPropertyFeatures()
	{
		global $conn;
		global $Action;
		global $propid;
		?>
		<table cellpadding="3">
			<?php
				if ($Action == "Update")
				{
					$query = "SELECT * From PropertyFeature WHERE pf_property = ".$propid;
					$stmt = oci_parse($conn,$query);
					oci_execute($stmt);
					
					$propFeatures = array();
					while ($propFeatRow = oci_fetch_array($stmt))
					{
						array_push($propFeatures,$propFeatRow["PF_FEATURE"]);
					}
					oci_free_statement($stmt);
				}
				
				$query = "SELECT * FROM Feature ORDER BY feat_id";
				$stmt = oci_parse($conn,$query);
				oci_execute($stmt);
				
				while ($featRow = oci_fetch_array($stmt))
				{
					?>		
					<tr> 
					<td>
					<input 
						type="checkbox" 
						name="feats[]" 
						value="<?php echo $featRow["FEAT_ID"]; ?>" 
						<?php if(isset($propFeatures) && in_array($featRow["FEAT_ID"],$propFeatures)){echo "checked";}?>
					>
						<?php echo $featRow["FEAT_NAME"]; ?> 
					</input>
					</td>
					</tr>
					<?php
					
				}
				oci_free_statement($stmt);
			?>
			</table><br/>
			<?php
	}
	
?>
