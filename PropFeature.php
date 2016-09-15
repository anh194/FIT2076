<?php
	ob_start();
	session_start();
	
	include 'functions.php';
	login("PropFeature.php");
?> 

<html>
	<body>
		<table border = "1">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Description</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		<?php
			include("connection.php");
			$conn = oci_connect($UName,$PWord,$DB);
			$query = "SELECT * FROM FEATURE ORDER BY FEAT_NAME";
			$stmt = oci_parse($conn,$query);
			oci_execute($stmt);
			
			while ($row = oci_fetch_array ($stmt))
			{
				echo "<tr>";
				echo "<td>$row[FEAT_ID]</td>";
				echo "<td>$row[FEAT_NAME]</td>";
				echo "<td>$row[FEAT_DESCRIPTION]</td>";
				
				echo "<td><a href = \"PropFeatModify.php?featid=$row[FEAT_ID]&Action=Update\">Edit</a></td>";
				echo "<td><a href = \"PropFeatModify.php?featid=$row[FEAT_ID]&Action=Delete\">Delete </a></td>";
				echo "</tr>";
			}
		?>
		</table>
	</body>
</html>