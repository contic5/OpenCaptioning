<?php
require("adminauth.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
include("db.php");
?>
<?php
if(isset($_POST['Name']))
{
  $name=sanitize($_POST["Name"]);
  $location=trim($_POST["Location"]);
  $query=sprintf("INSERT INTO Memes(Name,Location)"
  ."VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",$name,$location);
  $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
  print("<p>".$name." added</p>");
  $categories=array("Classic","Anime/Gaming","Modern","TV/Movie","Other");
  for($i=0;$i<sizeof($categories);$i++)
  {
    $curcategory=$categories[$i];
    if(isset($_POST[$curcategory])&&$_POST[$curcategory])
    {
      $curcategorytext=str_replace("+"," ",$curcategory);
      $query=sprintf("INSERT INTO MemeCategory(Name,Category)"
      ."VALUES('%s','%s','%s')",$name,$curcategorytext);
      $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
      print("<p>".$name." set as ".$curcategorytext." game</p>");
    }
  }
}
function sanitize($text)
{
  $text=trim($text);
  $text=stripslashes($text);
  $text=htmlspecialchars($text);
  return $text;
}
?>
<h1>Insert Meme</h1>
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
