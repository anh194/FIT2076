<?php
	ob_start();
	session_start();
	
	$file = $_GET["filename"];
?>

<h1>
	Source Code for: <?php echo $file; ?> 
</h1>

<?php
	highlight_file($file);
?>