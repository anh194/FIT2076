<html>
<head>
<meta charset="UTF-8">
<title></title>
</head>

<body>
	<center><h3>New client</h3></center>
 
<?php
	if (empty($_POST["cust_fname"]))
	{
?>
		<form method="post" action="AddClient.php">
			<center>Add<br/></center>
			
			<table align ="center" cellpadding="3">
				
				<tr>
					<td><b>Customer First Name</b></td>
					<td><input type="text" name="cust_fname" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Customer Last Name</b></td>
					<td><input type="text" name="cust_lname" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Customer Home Phone</b></td>
					<td><input type="text" name="cust_homephone" size="30"></td>
				</tr>

				<tr>
					<td><b>Customer Mobile Phone</b></td>
					<td><input type="text" name="cust_mobilephone" size="30"></td>
				</tr>
				
				<tr>
					<td><b>Customer Email</b></td>
					<td><input type="text" name="cust_email" size="30"></td>
				</tr>

				<tr>
					<td><b>Customer Mailing list</b></td>
					<!--td><input type="text" name="cust_mailinglist" size="30"></td -->
					<td><input type="radio" name="cust_mailinglist" value="y" checked> Yes </td><br>
					<td><input type="radio" name="cust_mailinglist" value="n"> No</td>
				</tr>

				<tr>
					<td><b>Customer Country</b></td>
					<td><input type="text" name="cust_country" size="30"></td>
				</tr>

				<tr>
					<td><b>Customer State</b></td>
					<td><input type="text" name="cust_state" size="30"></td>
				</tr>

				<tr>
					<td><b>Customer City</b></td>
					<td><input type="text" name="cust_city" size="30"></td>
				</tr>

				<tr>
					<td><b>Customer Postcode</b></td>
					<td><input type="text" name="cust_postcode" size="30"></td>
				</tr>

				<tr>
					<td><b>Customer Street</b></td>
					<td><input type="text" name="cust_street" size="30"></td>
				</tr>

			</table> <br/>
			<table align="center">
				<tr>
				<td><input type = "submit" value = "Add Customer"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='customers.php';"/></td>
				</tr>
			</table>
		</form>
<?php
    }
    else
    {
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die("Couldn't logon.");
    $query="INSERT INTO CUSTOMER VALUES (cust_seq.nextval, '$_POST[cust_fname]', '$_POST[cust_lname]', '$_POST[cust_homephone]', '$_POST[cust_mobilephone]',
		'$_POST[cust_email]', '$_POST[cust_mailinglist]', '$_POST[cust_country]', '$_POST[cust_state]', '$_POST[cust_city]',
		'$_POST[cust_postcode]', '$_POST[cust_street]')" ;
	
	$stmt = oci_parse($conn,$query);
	//oci_execute($stmt);
	if(@oci_execute($stmt))
	{
	?>
	<script language="JavaScript">
		alert("Customer record successfully added to database");
	</script>
	<?php	  
       

      $query = "SELECT * FROM Customer";
      $stmt = oci_parse($conn,$query);
	  oci_execute($stmt);
?>
      <table border="1" align="center">
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
			</tr>
<?php
      while ($row = oci_fetch_array ($stmt))
      {
?>
        <tr>
          <td><?php echo $row["CUST_ID"];  ?></td>
          <td><?php echo $row["CUST_FNAME"];  ?></td>
          <td><?php echo $row["CUST_LNAME"];  ?></td>
          <td><?php echo $row["CUST_HOMEPHONE"];  ?></td>
          <td><?php echo $row["CUST_MOBILEPHONE"];  ?></td>
          <td><?php echo $row["CUST_EMAIL"];  ?></td>
          <td><?php echo $row["CUST_MAILINGLIST"];  ?></td>
          <td><?php echo $row["CUST_COUNTRY"];  ?></td>
          <td><?php echo $row["CUST_STATE"];  ?></td>
          <td><?php echo $row["CUST_CITY"];  ?></td>
          <td><?php echo $row["CUST_POSTCODE"];  ?></td>
		  <td><?php echo $row["CUST_STREET"];  ?></td>
          
        </tr>
<?php
      }
?>
      </table>
      <p><a href="customers.php">Back</a></p>
<?php

	    }
		
		 else
        { 
		?>
 
          <script language="JavaScript">
            alert("Error adding record. Contact System Administrator");
          </script>
	<?php
	}	
	oci_free_statement($stmt);
	oci_close($conn); 
	//header("Location: customers.php");
	} ?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	