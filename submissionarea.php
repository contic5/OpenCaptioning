<?php
require("sessionstart.php");
require("db.php");
print("<h3>Actions</h3>
<form method='post'>
<p>Enter your answer</p>
<p><textarea rows='5' cols='40' name='answer'></textarea></p>
<button type='submit'>Submit Answer</button>
</form>");
$username=$_SESSION["username"];
$code=$_SESSION["code"];
$query="SELECT * FROM Games WHERE Code='$code'";
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$host='';
while($row=mysqli_fetch_assoc($result))
{
  $host=$row["Host"];
}
if($username==$host)
{
    print("<form method='post'>
    <input type='hidden' name='nextround'>
    <button type='submit'>Next Round</button>
    </form>
    <form method='post'>
    <input type='hidden' name='showanswers'>
    <button type='submit'>Show Answers</button>
    </form>
    <form method='post'>
    <input type='hidden' name='endgame'>
    <button type='submit'>End Game</button>
    </form>");
}
else
{
    print("<form method='post'>
    <input type='hidden' name='exitgame'>
    <button type='submit'>Exit Game</button>
    </form>");
}
if($username==$host)
{
  print("<h3>Change Meme</h3>");
  $query="SELECT * FROM Games WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  while($row=mysqli_fetch_assoc($result))
  {
    $category=$row["Category"];
  }
  $query="";
  if($category=="All")
  {
    $query="SELECT * FROM Memes ORDER BY Name ASC";
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
    $query="SELECT * FROM Memes WHERE Name in ('$correctmemetext') ORDER BY Name ASC";
  }
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  print("<form method='post'>");
  print("<p><select name='nextmeme'>");
  while($row=mysqli_fetch_assoc($result))
  {
    $meme=$row["Name"];
    print("<option value='$meme'>".$meme."</option>");
  }
  print("</select></p>");
  print("<p><button type='submit'>Change Meme</button></p>");
  print("</form>");
}
?>
