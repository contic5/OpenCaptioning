<?php
require("adminauth.php");
?>
<html>
<head>
<script type="text/javascript" src="removewatermark.js"></script>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<p><a href="viewgames.php">View Games</a></p>
<?php
include("db.php");
if(isset($_POST["Code"]))
{
  $code=$_POST["Code"];
  $query=sprintf("DELETE FROM Games WHERE Code='%s'",$code);
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $query = "UPDATE Players SET Answer='',CurrentGame='' WHERE CurrentGame='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  print("<p>Deleted ".$code."</p>");
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
