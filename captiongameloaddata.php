<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
require("adminauth.php");
$conn=mysqli_connect("localhost", "cjcuser", "computing", "CaptionGame");
$imagelocations = array_diff(scandir("captiongameimages"), array('..', '.'));
$imagelocations = array_values($imagelocations);
$names=array_values($imagelocations);
$query="TRUNCATE TABLE Memes";
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$query="SELECT * FROM Memes";
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$rowcount=mysqli_num_rows($result);
for($i=0;$i<sizeof($names);$i++)
{
  $names[$i]=str_replace("-"," ",$names[$i]);
  $names[$i]=str_replace(".jpg","",$names[$i]);
  print($imagelocations[$i]." ".$names[$i]."<br>");
  if($rowcount<=0)
  {
    $curimagelocation=$imagelocations[$i];
    $curname=$names[$i];
    $query="INSERT INTO Memes(ID,Name,Location)"."VALUES('$i','$curname','$curimagelocation')";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  }
}
print(sizeof($imagelocations));
?>
</body>
</html>
