<?php
session_start();
?>

<html>
<head><title>Upload the Image</title></head>
<body>
<h1> Upload Image</h1>


<form enctype="multipart/form-data" action="uploader.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

<input type="file" name="userfile" />
<input type="Submit" value="Submit" />
</form>

</body>
</html>
