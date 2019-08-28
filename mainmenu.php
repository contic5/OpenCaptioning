<?php
require("sessionstart.php");
require("auth.php");
include("db.php");
?>
<html>
<head>
<title>Open Captioning Menu</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<script type="text/javascript" src="removewatermark.js"></script>
</head>
<body>
<?php
$username=$_SESSION["username"];
if(isset($_SESSION["code"])&&$_SESSION["code"]!="")
{
  header("Location: playcaptiongame.php");
}
if(isset($_POST["option"])&&$_POST["option"]=="create")
{
  $code=$_POST["code"];
  $username=$_SESSION["username"];
  $category=$_POST["category"];
  $query=sprintf("SELECT * FROM Games WHERE Code='%s'",$code);
  $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
  $unique=true;
  $rows = mysqli_num_rows($result);
  if($rows!=0)
  {
    $unique=false;
  }
  if($unique)
  {
    $_SESSION["code"]=$code;
    if($category=="All")
    {
      $query="SELECT * FROM Memes";
      $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
      $rowcount=mysqli_num_rows($result);
      $CURID=rand(0,$rowcount);
      $query="SELECT * FROM Memes WHERE ID='$CURID'";
      $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
    }
    else
    {
      $query="SELECT * FROM MemeCategory WHERE Category='$category'";
      $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
      $correctmemes=[];
      while($row=mysqli_fetch_assoc($result))
      {
        array_push($correctmemes,$row["Name"]);
      }
      $correctmemetext = implode("', '", $correctmemes);
      $query="SELECT * FROM Memes WHERE Name in ('$correctmemetext')";
      $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
      $rowcount=mysqli_num_rows($result);
      $IDS=[];
      while($row=mysqli_fetch_assoc($result))
      {
        array_push($IDS,$row["ID"]);
      }
      $CURID=$IDS[rand(0,$rowcount)];
      $query="SELECT * FROM Memes WHERE ID='$CURID'";
      $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
    }
    $curimagename="";
    $curimagelocation="";
    while($row=mysqli_fetch_assoc($result))
    {
      $curimagename=$row["Name"];
      $curimagelocation=$row["Location"];
    }
    $query="SELECT * FROM Games";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
    $rowcount=mysqli_num_rows($result);
    $query="INSERT INTO Games(ID,Code,ImageName,ImageLocation,Host,AnswersVisible,Category)"."VALUES('$rowcount','$code','$curimagename','$curimagelocation','$username',0,'$category')";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));

    $query="SELECT * FROM Players";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
    $rowcount=mysqli_num_rows($result);

    $query="UPDATE Players SET CurrentGame='$code',Answer='' WHERE Username='$username'";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
    $_SESSION["username"]=$username;
    header("Location: playcaptiongame.php");
  }
  else
  {
    print("<p>A game already exists with that code. Choose a different code.</p>");
  }
}
if(isset($_POST["option"])&&$_POST["option"]=="join")
{
  $username=$_SESSION["username"];
  $code=$_POST["code"];
  $query="UPDATE Players SET CurrentGame='$code',Answer='' WHERE Username='$username'";
  $_SESSION["code"]=$code;
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $_SESSION["username"]=$username;
  header("Location: playcaptiongame.php");
}
?>
<h1>Open Captioning</h1>
<h2>Main Menu</h2>
<?php
print("<p>Hello ".$_SESSION["username"]."</p>");
if($_SESSION["Usertype"]=="Admin")
{
  print("<p>Hello Admin</p>");
  print("<table><tbody><tr>");
  print("<td><a href='addmemeform.php'>Add Meme</td>");
  print("<td><a href='viewmemes.php'>View Memes</td>");
  print("<td><a href='viewgames.php'>View Games</td>");
  print("</tr></tbody></table>");
}
?>
<p>Create Game</p>
<form method="post">
<input type="hidden" name="option" value="create">
<p>Code:<input maxlength='5' size='5' name="code"></input></p>
<p>Category:
<select name="category">
<option value="All">All</option>
<option value="Classic">Classic</option>
<option value="Modern">Modern</option>
<option value="Anime/Gaming">Anime/Gaming</option>
<option value="TV/Movie">TV/Movie</option>
<option value="Other">Other</option>
</select>
</p>
<button type="submit">Submit</button>
</form>
<p>Join Game</p>
<form method="post">
<input type="hidden" name="option" value="join">
<p>Code:<input name="code"></input></p>
<p><button type="submit">Submit</button></p>
<p><a href="logout.php">Logout</a></p>
</form>
</body>
</html>
