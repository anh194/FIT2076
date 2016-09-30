<?php
	ob_start();

?> 

<html>
	<body>
		<table border = "1">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		<?php
			include("connection.php");
			$conn = oci_connect($UName,$PWord,$DB);
			$query = "SELECT * FROM PROPERTYTYPE ORDER BY PTYPE_NAME";
			$stmt = oci_parse($conn,$query);
			oci_execute($stmt);
	
			while ($row = oci_fetch_array ($stmt))
			{
				echo "<tr>";
				echo "<td>$row[PTYPE_ID]</td>";
				echo "<td>$row[PTYPE_NAME]</td>";
				
				echo "<td><a href = \"PropModify.php?propid=$row[PTYPE_ID]&Action=Update\">Edit</a></td>";
				echo "<td><a href = \"PropModify.php?propid=$row[PTYPE_ID]&Action=Delete\">Delete </a></td>";
				echo "</tr>";
			}
		?>
		</table>
	</body>
</html>