<?php
require("adminauth.php");
?>
<html>
<head>
<title>View Memes</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
include("db.php");
?>
<?php
$query="SELECT * FROM Memes";
if(isset($_GET["searchtype"]))
{
  $searchtype=$_GET["searchtype"];
  if($searchtype=="A-Z")
  {
    $query="SELECT * FROM Memes ORDER BY Name ASC";
  }
  if($searchtype=="Z-A")
  {
    $query="SELECT * FROM Memes ORDER BY Name DESC";
  }
  if($searchtype=="1-X")
  {
    $query="SELECT * FROM Memes ORDER BY ID ASC";
  }
  if($searchtype=="X-1")
  {
    $query="SELECT * FROM Memes ORDER BY ID DESC";
  }
}
$result=mysqli_query($conn,$query);
print("<h2>View Memes</h2>");
print("<table><tbody>");
print("<tr><td colspan='5'><a href='addmemeform.php'>Insert Board Game</a></td></tr>");
while($row=mysqli_fetch_assoc($result))
{
  $rowon=$row["ID"];
  $name=$row["Name"];
  print("<tr>");
  print("<form method='get' action='editmeme.php'>");
  print("<input type='hidden' name='ID' value='$rowon'>");
  print("<td>".$row["ID"]."</td>");
  print("<td>".$row["Name"]."</td>");
  print("<td><button type='submit'>Edit</button></td>");
  print("</form>");
  print("<form method='post' action='deletememe.php'>");
  print("<input type='hidden' name='Name' value='$name'>");
  print("<td><button type='submit'>Delete</button></td>");
  print("</form>");
  print("</tr>");
}
print("</tbody></table>");
?>
</body>
</html>
