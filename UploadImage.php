<?php
	//images
	if (!isset($_FILES["userfile"]["tmp_name"]))
	{?>
		<form method="post" enctype="multipart/form-data" action="UploadImage.php">
			<table border="0">
				<tr>
					<td><b>Select a file to upload:</b><br>
					<input type="file" size="50" name="userfile">
					</td>
				</tr>
					<tr>
					<td><input type="submit" value="Upload File"> </td>
				</tr>
			</table>
		</form>
		<?php
	}
	else
	{
		$upfile = "property_images/".$_FILES["userfile"]["name"];

		if (!move_uploaded_file($_FILES["userfile"]["tmp_name"],$upfile))
		{
			echo "ERROR: Could not move file into directory";
		}
		else
		{
			echo "Temporary File Name: " .$_FILES["userfile"] ["tmp_name"]."<br />";
			echo "File Name: " .$_FILES["userfile"]["name"]. "<br />";
			echo "File Size: " .$_FILES["userfile"]["size"]. "<br />";
			echo "File Type: " .$_FILES["userfile"]["type"]. "<br />"; 
		}
	}
?>