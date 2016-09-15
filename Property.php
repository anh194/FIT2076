<?php
	ob_start();
	session_start();
	
	include 'functions.php';
	login("Property.php");
			
?>
	
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
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		<?php
			include("connection.php");
			$conn = oci_connect($UName,$PWord,$DB);
			
			$propQuery = "SELECT * FROM PROPERTY ORDER BY PROP_STREET";
			$propStmt = oci_parse($conn,$propQuery);
			oci_execute($propStmt);

			while ($propRow = oci_fetch_array ($propStmt))
			{
				echo "<tr>";
				echo "<td>$propRow[PROP_ID]</td>";
				$address = "$propRow[PROP_STREET] <br/> $propRow[PROP_POSTCODE], $propRow[PROP_CITY], $propRow[PROP_STATE], $propRow[PROP_COUNTRY]";
				echo "<td>$address</td>";
				echo "<td>$propRow[PROP_BEDROOMS]</td>";
				echo "<td>$propRow[PROP_BATHROOMS]</td>";
				echo "<td>$propRow[PROP_DESCRIPTION]</td>";
				
				$typeQuery = "SELECT ptype_name FROM PropertyType WHERE PTYPE_ID = ".$propRow["PROP_TYPE"];
				$typeStmt = oci_parse($conn,$typeQuery);
				oci_execute($typeStmt);
				
				if ($typeRow = oci_fetch_array($typeStmt))
				{
					echo "<td>$typeRow[PTYPE_NAME]</td>";
				}
				else 
				{
					echo "<td>none</td>";
				}
				
				oci_free_statement($typeStmt);
				
				echo "<td><a href = \"PropModify.php?propid=$propRow[PROP_ID]&Action=Update\">Edit</a></td>";
				echo "<td><a href = \"PropModify.php?propid=$propRow[PROP_ID]&Action=Delete\">Delete </a></td>";
				echo "</tr>";
			}
			
			oci_free_statement($propStmt);
			oci_close($conn);
			
		?>
		</table>
	</body>
</html>