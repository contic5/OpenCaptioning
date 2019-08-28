<?php
require("adminauth.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<h1>Open Captioning</h1>
<h2>Edit Meme</h2>
<?php
include("db.php");
?>
<?php
$name="";
$category=0;
$location="";
$categories=array("Classic","Anime/Gaming","Modern","TV/Movie","Other");
$categoriesset=array_fill(0, count($categories), "");
$ID=0;
$MAXID=0;
$query="SELECT * FROM Memes";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$MAXID=mysqli_num_rows($result);
if(isset($_GET['ID']))
{
  $ID=sanitize($_GET['ID']);
  $query=sprintf("SELECT * FROM Memes where ID='%d'",$ID);
  $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
  while($row = mysqli_fetch_assoc($result))
  {
    $name=$row["Name"];
    $location=$row["Location"];
  }
  print("<img src='captiongameimages/".$location."'></img>");
  print("<form method='get'>");
  if($ID>1)
  {
    print("<input type='hidden' name='ID' value='".($ID-1)."'>");
    print("<p><button type='submit'>Edit Previous</button></p>");
    print("</form>");
  }
  else
  {
    print("<input type='hidden' name='ID' value='".($ID-1)."'>");
    print("<p><button disabled type='submit'>Edit Previous</button></p>");
    print("</form>");
  }
  if($ID<$MAXID)
  {
    print("<form method='get'>");
    print("<input type='hidden' name='ID' value='".($ID+1)."'>");
    print("<p><button type='submit'>Edit Next</button></p>");
    print("</form>");
  }
  else
  {
    print("<form method='get'>");
    print("<input type='hidden' name='ID' value='".($ID+1)."'>");
    print("<p><button disabled type='submit'>Edit Next</button></p>");
    print("</form>");
  }
}
if(isset($_POST['Name']))
{
  $ID=sanitize($_POST["ID"]);
  $name=sanitize($_POST["Name"]);
  $location=trim($_POST["Location"]);
  $location=htmlspecialchars($location);
  $query=sprintf("UPDATE Memes SET Name='%s',Location='%s'"
  ."where ID='%d'",$name,$location,$ID);
  $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
  print("<p>".$name." updated</p>");
  for($i=0;$i<sizeof($categories);$i++)
  {
    $curcategory=$categories[$i];
    if(isset($_POST[$curcategory])&&$_POST[$curcategory])
    {
      $curcategorytext=str_replace("+"," ",$curcategory);
      $query=sprintf("SELECT * FROM MemeCategory WHERE Name='%s' AND Category='%s' ",$name,$curcategorytext);
      $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
      $rowcount=mysqli_num_rows($result);
      if($rowcount==0)
      {
        $query=sprintf("INSERT INTO MemeCategory(Name,Category)"
        ."VALUES('%s','%s')",$name,$curcategorytext);
        $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
        print("<p>".$name." is now set as a ".$curcategorytext." meme</p>");
      }
      else
      {
        print("<p>".$name." is already set as a ".$curcategorytext." meme</p>");
      }
    }
    else
    {
      $curcategorytext=str_replace("+"," ",$curcategory);
      $query=sprintf("DELETE FROM MemeCategory WHERE Name='%s' AND Category='%s'",$name,$curcategorytext);
      $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
      $rowcount=mysqli_affected_rows($conn);
      if($rowcount>0)
      {
        print("<p>".$name." is no longer set as a ".$curcategorytext." meme</p>");
      }
    }
  }
}
if(isset($_GET['ID']))
{
  $query=sprintf("SELECT * FROM Memes WHERE Name='%s'",$name);
  $result = mysqli_query($conn, $query) or die ( mysqli_error($conn));
  while($row = mysqli_fetch_assoc($result))
  {
    $location=$row["Location"];
  }
  for($i=0;$i<sizeof($categories);$i++)
  {
    $curcategory=$categories[$i];
    $curcategorytext=str_replace("+"," ",$curcategory);
    $query=sprintf("SELECT * FROM MemeCategory WHERE Name='%s' AND Category='%s'",$name,$curcategorytext);
    $result = mysqli_query($conn, $query) or die ( mysqli_error($conn));
    $rowcount=mysqli_num_rows($result);
    if($rowcount>0)
    {
      $categoriesset[$i]="checked";
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
<h1>Edit Meme</h1>
<form method="post">
<input type="hidden" name="ID" value=<?php print($_GET["ID"])?>>
<p>Name:
<input type="text" name="Name" size='64' required
<?php
print("value='$name'"); ?>
>
</p>
<p>Location:</p>
<p><textarea name="Location" cols='32' rows='8' required>
<?php print($location); ?>
</textarea></p>
<p>Categories</p>
Classic:<input type="checkbox" name="Classic"
<?php
print($categoriesset[0]);
?>
><br>
Anime/Gaming:<input type="checkbox" name="Anime/Gaming"
<?php
print($categoriesset[1]);
?>
><br>
Modern:<input type="checkbox" name="Modern"
<?php
print($categoriesset[2]);
?>
><br>
TV/Movie:<input type="checkbox" name="TV/Movie"
<?php
print($categoriesset[3]);
?>
><br>
Other:<input type="checkbox" name="Other"
<?php
print($categoriesset[4]);
?>
><br>
<button type="submit">Submit</button>
</form>
</body>
</html>
