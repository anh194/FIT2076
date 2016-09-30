
<?php
	ob_start();
	session_start();
	
	if (isset($_GET["custid"]) && isset($_GET["Action"]))
	{
		$_SESSION["custid"] = $_GET["custid"];
		$_SESSION["Action"] = $_GET["Action"];
	}
	$custid = $_SESSION["custid"];
	$Action = $_SESSION["Action"];
?>
	
<html>
<body>
	<?php
	include("connection.php");
	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
	
	$query="SELECT * FROM CUSTOMER WHERE CUST_ID = ".$custid;
	$stmt = oci_parse($conn,$query);
	oci_execute($stmt);
	$row = oci_fetch_array($stmt);
	include("functions.php");
	if (login("CustModify.php"))
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
			case "Add":
				Add();
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
		global $custid;
		global $row;
		
		?>
		<form method = "post" action = "CustModify.php?custid=<?php echo $custid;?>&Action=ConfirmUpdate">
			<center>Testing<br/></center>
			
			<table align ="center" cellpadding="3">
			
				<tr>
					<td><b>Customer ID</b></td>
					<td><?php echo $row["CUST_ID"];?></td>
				</tr>
				
				<tr>
					<td><b>Customer First Name</b></td>
					<td><input type="text" name="cust_fname" size="30" value="<?php echo $row["CUST_FNAME"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Customer Last Name</b></td>
					<td><input type="text" name="cust_lname" size="30" value="<?php echo $row["CUST_LNAME"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Customer Home Phone</b></td>
					<td><input type="text" name="cust_homephone" size="30" value="<?php echo $row["CUST_HOMEPHONE"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer Mobile Phone</b></td>
					<td><input type="text" name="cust_mobilephone" size="30" value="<?php echo $row["CUST_MOBILEPHONE"]; ?>"></td>
				</tr>
				
				<tr>
					<td><b>Customer Email</b></td>
					<td><input type="text" name="cust_email" size="30" value="<?php echo $row["CUST_EMAIL"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer Mailing list</b></td>
					<td><input type="text" name="cust_mailinglist" size="30" value="<?php echo $row["CUST_MAILINGLIST"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer Country</b></td>
					<td><input type="text" name="cust_country" size="30" value="<?php echo $row["CUST_COUNTRY"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer State</b></td>
					<td><input type="text" name="cust_state" size="30" value="<?php echo $row["CUST_STATE"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer City</b></td>
					<td><input type="text" name="cust_city" size="30" value="<?php echo $row["CUST_CITY"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer Postcode</b></td>
					<td><input type="text" name="cust_postcode" size="30" value="<?php echo $row["CUST_POSTCODE"]; ?>"></td>
				</tr>

				<tr>
					<td><b>Customer Street</b></td>
					<td><input type="text" name="cust_street" size="30" value="<?php echo $row["CUST_STREET"]; ?>"></td>
				</tr>

			</table> <br/>

			<table align="center">
				<tr>
				<td><input type = "submit" value = "Update Customer"></td>
				<td><input type = "button" value = "Return to List" onclick="window.location.href='customers.php';"/></td>
				</tr>
			</table>
		</form>

		<?php
	}
	function ConfirmUpdate()
	{
		global $conn;
		global $custid;

		$query="UPDATE Customer set CUST_STREET = '$_POST[cust_street]',
		CUST_POSTCODE = '$_POST[cust_postcode]',
		CUST_CITY = '$_POST[cust_city]',
		CUST_STATE = '$_POST[cust_state]',
		CUST_COUNTRY = '$_POST[cust_country]',
		CUST_MAILINGLIST = '$_POST[cust_mailinglist]',
		CUST_EMAIL = '$_POST[cust_email]',
		CUST_MOBILEPHONE = '$_POST[cust_mobilephone]',
		CUST_HOMEPHONE = '$_POST[cust_homephone]',
		CUST_LNAME = '$_POST[cust_lname]',
		CUST_FNAME = '$_POST[cust_fname]'
		WHERE cust_id = ".$custid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);
		header("Location: customers.php");
		exit;
	}
	function Del()
	{
		global $conn;
		global $custid;
		
		?>
		<center>
		<script language="javascript"> 
			var text =  "Are you sure you want to delete <?php echo $custid;?>?"
			if(window.confirm(text))
			{
				window.location='CustModify.php?custid=<?php echo $custid; ?>&Action=ConfirmDelete';
			}else
			{
				window.location='customers.php';
			}
		</script>
		</center><?php
	}
	function ConfirmDel()
	{
		global $conn;
		global $custid;
		
		$query="DELETE FROM Customer WHERE cust_id = ".$custid;
		$stmt = oci_parse($conn,$query);
		oci_execute($stmt);
		oci_free_statement($stmt);
		header("Location: customers.php");
		exit;
	}
?>

	<?php
	function Add()
	{
	?>
		<form method = "post" action = "CustModify.php?Action=ConfirmAdd">
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
					<td><input type="text" name="cust_mailinglist" size="30"></td>
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
	function ConfirmAdd()
	{
		$query="INSERT INTO Customer 
		VALUES ((cust_no_seq.nextval, '$_POST[cust_street]', '$_POST[cust_postcode]', '$_POST[cust_city]', '$_POST[cust_state]',
		'$_POST[cust_country]', '$_POST[cust_mailinglist]', '$_POST[cust_email]', '$_POST[cust_mobilephone]', '$_POST[cust_homephone]',
		'$_POST[cust_lname]', '$_POST[cust_fname]')" ;
		$stmt = oci_parse($conn,$query);
		if(@oci_execute($stmt));
		{
		?>
		<script language="JavaScript">
			alert("Customer record successfully added to database");
		</script>
		<?php
		}
		oci_execute($stmt);
		oci_free_statement($stmt);
		header("Location: customers.php");
		exit;
		
	}

	
