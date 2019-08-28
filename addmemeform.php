<?php
require("adminauth.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<title>Add Meme Form</title>
<body>
<?php
include("db.php");
?>
<h1>Open Captioning</h1>
<h2>Add Meme</h2>
<form method="post" action="addmeme.php" enctype="multipart/form-data">
<p>Select image to upload:
<input type="file" name="fileToUpload" id="fileToUpload"></p>
<p>Categories</p>
Classic:<input type="checkbox" name="Classic"><br>
Anime/Gaming:<input type="checkbox" name="Anime/Gaming"><br>
Modern:<input type="checkbox" name="Modern"><br>
TV/Movie:<input type="checkbox" name="TV/Movie"><br>
Other:<input type="checkbox" name="Other"><br>
<button type="submit">Submit</button>
</form>
</body>
</html>
