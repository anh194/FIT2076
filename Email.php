
<?php
	ob_start();
	session_start();
	
	include 'functions.php';
			
?>

<html>
<head>
<meta charset="UTF-8">
<title>What a ***** language</title>
</head>
<body>
<?php 
include("connection.php");
if (!isset($_POST["to"]))
{
	$query = "SELECT * FROM CUSTOMER WHERE cust_mailinglist = 'y' ";

	$conn = oci_connect($UName,$PWord,$DB) or die ("Could not connect to database.");
	$stmt = oci_parse($conn,$query);
	oci_execute($stmt); ?>

<form method="post" action="Email.php">

<b>Subject</b><br>
<input type="text" name="subject" size="30"><br>
Select customer<br>
<?php
	while ($row = oci_fetch_array ($stmt))
	{
?>

		<input type="checkbox" name="to[]" value="<?php echo $row['CUST_EMAIL']; ?>"> <?php echo $row['CUST_FNAME']; ?> <br>

<?php
	} ?>
	
	<b>Message</b><br>
	<textarea id="message" class="text" cols="86" rows ="20" name="message"></textarea><br>
	<input type = "submit" value = "send email"><br>
	</form>
<?php
	
}
else
{
	// $mail->Mailer = "smtp";  
	// $mail->Host = "ssl://smtp.gmail.com";  
	// $mail->Port = 465;  
	// $mail->SMTPAuth = true; // turn on SMTP authentication  
	// $mail->Username = "anh194227@gmail.com"; // SMTP username  
	// $mail->Password = "Frostnova1"; // SMTP password 
	$from = "From:Minh Anh Tran <matra4@student.monash.edu>";
	//$to = implode(',', $_POST["to"]);
	$to = implode(',', $_POST['to']);
	$subject = $_POST["subject"];
	$message = $_POST["message"];
	// mail($to, $subject, $message, $from);
	if (mail($to, $subject, $message, $from))
	{
		echo "Mail sent";
	}
	else{
		echo "error sending mail";
	}
}
?>

</body>
</html>