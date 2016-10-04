<?php
	ob_start();
	session_start();
	
	include 'functions.php';
	login("customers.php");	
			
?>
	
<html>
	<body>
		<table border = "1">
			<tr>
				<th>ID</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Home phone/th>
				<th>Mobile phone</th>
				<th>Email</th>
				<th>Mailing list</th>
				<th>Country</th>
				<th>State</th>
				<th>City</th>
				<th>Postcode</th>
				<th>Street</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		<?php
			include("connection.php");
			$conn = oci_connect($UName,$PWord,$DB);
			
			$custQuery = "SELECT * FROM CUSTOMER ORDER BY CUST_ID";
			$custStmt = oci_parse($conn,$custQuery);
			oci_execute($custStmt);

			while ($custRow = oci_fetch_array ($custStmt))
			{
				echo "<tr>";
				echo "<td>$custRow[CUST_ID]</td>";
				//$address = "$custRow[cust_STREET] <br/> $custRow[cust_POSTCODE], $custRow[cust_CITY], $custRow[cust_STATE], $custRow[cust_COUNTRY]";
				//echo "<td>$address</td>";
				echo "<td>$custRow[CUST_FNAME]</td>";
				echo "<td>$custRow[CUST_LNAME]</td>";
				echo "<td>$custRow[CUST_HOMEPHONE]</td>";
				echo "<td>$custRow[CUST_MOBILEPHONE]</td>";
				echo "<td>$custRow[CUST_EMAIL]</td>";
				echo "<td>$custRow[CUST_MAILINGLIST]</td>";
				echo "<td>$custRow[CUST_COUNTRY]</td>";
				echo "<td>$custRow[CUST_STATE]</td>";
				echo "<td>$custRow[CUST_CITY]</td>";
				echo "<td>$custRow[CUST_POSTCODE]</td>";
				echo "<td>$custRow[CUST_STREET]</td>";
				
				echo "<td><a href = \"CustModify.php?custid=$custRow[CUST_ID]&Action=Update\">Edit</a></td>";
				echo "<td><a href = \"custModify.php?custid=$custRow[CUST_ID]&Action=Delete\">Delete </a></td>";
				echo "</tr>";
			}
		?>
			<a href = "AddClient.php">Add </a>
			<a href = "Email.php">Email </a>
		<?php
			oci_free_statement($custStmt);
			//oci_free_statement($typeStmt);
			oci_close($conn);
		?>
		</table>
		 
	</body>
</html>