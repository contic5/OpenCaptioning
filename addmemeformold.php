<?php
require("sessionstart.php");
require("adminauth.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<script type="text/javascript" src="removewatermark.js"></script>
<title>Add Meme Form</title>
</head>
<body>
<?php
require("db.php");
?>
<h1>Open Captioning</h1>
<h2>Add Meme</h2>
<form action="addmeme.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br>
    <input type="submit" value="Add Meme" name="submit">
</form>
<p><a href="mainmenu.php">Return to Main Menu</a></p>
</body>
</html>
