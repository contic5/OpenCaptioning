<?php
require("adminauth.php");
?>
<html>
<head>
<title>View Games</title>
<script type="text/javascript" src="removewatermark.js"></script>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
include("db.php");
?>
<?php
$query="SELECT * FROM Games";
if(isset($_GET["searchtype"]))
{
  $searchtype=$_GET["searchtype"];
  if($searchtype=="A-Z")
  {
    $query="SELECT * FROM Games ORDER BY Name ASC";
  }
  if($searchtype=="Z-A")
  {
    $query="SELECT * FROM Games ORDER BY Name DESC";
  }
  if($searchtype=="1-X")
  {
    $query="SELECT * FROM Games ORDER BY ID ASC";
  }
  if($searchtype=="X-1")
  {
    $query="SELECT * FROM Games ORDER BY ID DESC";
  }
}
$result=mysqli_query($conn,$query);
print("<h2>View Games</h2>");
print("<p><a href='mainmenu.php'>Go to Main Menu</a></p>");
print("<table><tbody>");
print("<tr>");
print("<td>ID</td>");
print("<td>Code</td>");
print("<td>Delete</td>");
print("</tr>");
while($row=mysqli_fetch_assoc($result))
{
  $rowon=$row["ID"];
  $code=$row["Code"];
  print("<tr>");
  print("<form method='post' action='deletegame.php'>");
  print("<td>".$rowon."</td>");
  print("<td>".$code."</td>");
  print("<input type='hidden' name='Code' value='$code'>");
  print("<td><button type='submit'>Delete</button></td>");
  print("</form>");
  print("</tr>");
}
print("</tbody></table>");
?>
</body>
</html>
