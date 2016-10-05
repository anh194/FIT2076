<!-- <html>
<body>
<?php
	// ob_start();
	// session_start();
	
	// include 'functions.php';
			
?>

<?php
    include("connection.php");
    $keyword = $_POST['keyword'];
    $conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
    $result = "SELECT * FROM PROPERTY
    WHERE PROP_DESCRIPTION LIKE '%{$keyword}%'";
    $propStmt = oci_parse($conn,$result);
	oci_execute($propStmt);
	//var_dump($propStmt);
	while ($propRow = oci_fetch_array ($propStmt))
	{
		$address = 
					$propRow["PROP_STREET"]."<br/>".
					$propRow["PROP_CITY"].", ".
					$propRow["PROP_STATE"].", ".
					$propRow["PROP_POSTCODE"].", ".
					$propRow["PROP_COUNTRY"]; 
				$typeQuery = "SELECT ptype_name FROM PropertyType WHERE PTYPE_ID = ".$propRow["PROP_TYPE"];
				$typeStmt = oci_parse($conn,$typeQuery);
				oci_execute($typeStmt);
				if ($typeRow = oci_fetch_array($typeStmt))
				{
					$propType = $typeRow["PTYPE_NAME"];
				}
				else 
				{
					$propType = "none";
				}
				oci_free_statement($typeStmt);
				?>
				<tr>
					<td><?php echo $propRow["PROP_ID"]; ?></td>
					<td><?php echo $address; ?></td>
					<td><?php echo $propRow["PROP_BEDROOMS"]; ?></td>
					<td><?php echo $propRow["PROP_BATHROOMS"]; ?></td>
					<td><?php echo $propRow["PROP_DESCRIPTION"]; ?></td>
					<td><?php echo $propType; ?></td>
				</tr>
				<?php
			}
				?>
	}
 ?>
</body>
</html>
 -->

<html>
	<body>		
		
		<table border = "1">
			<tr>
				<th>ID</th>
				<th>Address</th>
				<th>Bedrooms</th>
				<th>Bathrooms</th>
				<th>Description</th>
				<th>Type</th>
				<th>View</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		<?php
		
			include("connection.php");
    		$keyword = $_POST['keyword'];
    		$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
    		$result = "SELECT * FROM PROPERTY
    		WHERE PROP_DESCRIPTION LIKE '%{$keyword}%' OR PROP_ID LIKE '%{$keyword}%'";
    		$propStmt = oci_parse($conn,$result);
			oci_execute($propStmt);

			while ($propRow = oci_fetch_array ($propStmt))
			{
				$address = 
					$propRow["PROP_STREET"]."<br/>".
					$propRow["PROP_CITY"].", ".
					$propRow["PROP_STATE"].", ".
					$propRow["PROP_POSTCODE"].", ".
					$propRow["PROP_COUNTRY"]; 
				$typeQuery = "SELECT ptype_name FROM PropertyType WHERE PTYPE_ID = ".$propRow["PROP_TYPE"]; 
				$typeStmt = oci_parse($conn,$typeQuery);
				oci_execute($typeStmt);
				if ($typeRow = oci_fetch_array($typeStmt))
				{
					$propType = $typeRow["PTYPE_NAME"];
				}
				else 
				{
					$propType = "none";
				}
				oci_free_statement($typeStmt);
				?>
				<tr>
					<td><?php echo $propRow["PROP_ID"]; ?></td>
					<td><?php echo $address; ?></td>
					<td><?php echo $propRow["PROP_BEDROOMS"]; ?></td>
					<td><?php echo $propRow["PROP_BATHROOMS"]; ?></td>
					<td><?php echo $propRow["PROP_DESCRIPTION"]; ?></td>
					<td><?php echo $propType; ?></td>
					
					<td><a href = "PropModify.php?propid=<?php echo $propRow["PROP_ID"]?>&Action=View">View</a></td>
					<td><a href = "PropModify.php?propid=<?php echo $propRow["PROP_ID"]?>&Action=Update">Edit</a></td>
					<td><a href = "PropModify.php?propid=<?php echo $propRow["PROP_ID"]?>&Action=Delete">Delete </a></td>
				</tr>
				<?php
			}
				?>
				<form method = "post" action="search.php">
					<input type="text" name="keyword" />
    				<input type="submit" value="Search" /> <br>
				</form>
				<a href="Property.php">Back</a>
			<?php
			oci_free_statement($propStmt);
			oci_close($conn);
			?>
		</table>
	</body>
</html>