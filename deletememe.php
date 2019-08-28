<?php
require("adminauth.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<p><a href="viewmemes.php">View Games</a></p>
<?php
include("db.php");
if(isset($_POST["Name"]))
{
  $name=$_POST["Name"];
  $query=sprintf("SELECT * FROM Memes WHERE Name='%s'",$name);
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $CURID=999999;
  while($row = mysqli_fetch_assoc($result))
  {
    $CURID=$row["ID"];
  }
  $query=sprintf("DELETE FROM Memes WHERE Name='%s'",$name);
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $query=sprintf("DELETE FROM Memes WHERE Name='%s'",$name);
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  print("<p>Deleted ".$GameName."</p>");
  $query=sprintf("UPDATE Memes SET ID=ID-1 WHERE ID>$CURID");
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  //header("Location: ViewBoardGame.php");
}
function sanitize($text)
{
  $text=trim($text);
  $text=stripslashes($text);
  $text=htmlspecialchars($text);
  return $text;
}
?>
</body>
</html>
