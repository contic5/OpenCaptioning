<?php
require("auth.php");
require("sessionstart.php");
include("db.php");
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<title>Play Open Captioning</title>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<script type="text/javascript" src="removewatermark.js"></script>
<script>
$(document).ready(function()
{
  setInterval(function(){
    $.ajax({
     type: "GET",
     url: "displaygameinfo.php",
     data: "user=success",
     success: function(msg){
       $("#gameinfo").html(msg);
     }
   });
  }, 1000);
});
$(document).ready(function()
{
  $.ajax({
    type: "GET",
    url: "submissionarea.php",
    data: "user=success",
    success: function(msg){
      $("#submissionarea").html(msg);
    }
  });
});
</script>
</head>
<body>
<h1>Play Open Captioning</h1>
<?php
$username=$_SESSION["username"];
$playercount=0;
$answers=[];
$category="";
if(isset($_POST["answer"]))
{
  $username=$_SESSION["username"];
  $answer=$_POST["answer"];
  $answer=str_replace("'","&#8217",$answer);
  $query = "UPDATE Players SET Answer='$answer' WHERE Username='$username'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
}
if(isset($_POST["nextround"])) //Host moves game to next round
{
  $username=$_SESSION["username"];
  $code=$_SESSION["code"];

  $query="SELECT * FROM Games WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  while($row=mysqli_fetch_assoc($result))
  {
    $category=$row["Category"];
  }
  $query="";
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
  $query="UPDATE Games SET ImageName='$curimagename',ImageLocation='$curimagelocation',AnswersVisible=0 WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));

  $query = "UPDATE Players SET Answer='' WHERE CurrentGame='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
}
if(isset($_POST["nextmeme"])) //Host moves game to next round
{
  $username=$_SESSION["username"];
  $code=$_SESSION["code"];
  $memename=$_POST["nextmeme"];
  $query="SELECT * FROM Memes WHERE Name='$memename'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $curimagename="";
  $curimagelocation="";
  while($row=mysqli_fetch_assoc($result))
  {
    $curimagename=$row["Name"];
    $curimagelocation=$row["Location"];
  }
  $query="UPDATE Games SET ImageName='$curimagename',ImageLocation='$curimagelocation',AnswersVisible=0 WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));

  $query = "UPDATE Players SET Answer='' WHERE CurrentGame='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
}
if(isset($_POST["exitgame"]))
{
  $query = "UPDATE Players SET Answer='',CurrentGame='' WHERE Username='$username'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $_SESSION["code"]="";
  header("Location: mainmenu.php");
}
if(isset($_POST["showanswers"]))
{
  $code=$_SESSION["code"];
  $query="UPDATE Games SET AnswersVisible=1 WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
}
if(isset($_POST["endgame"]))
{
  $code=$_SESSION["code"];
  $query = "UPDATE Players SET Answer='',CurrentGame='' WHERE CurrentGame='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $query = "DELETE FROM Games WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $_SESSION["code"]="";
  header("Location: mainmenu.php");
}
if(isset($_POST["changescoreplayer"]))
{
  $amount=$_POST["amount"];
  $changescoreplayer=$_POST["changescoreplayer"];
  if($amount==0)
  {
    $query = "UPDATE Players SET Score=0 WHERE Username='$changescoreplayer'";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  }
  else
  {
    $query = "UPDATE Players SET Score=Score+$amount WHERE Username='$changescoreplayer'";
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  }

}
?>
<div id="gameinfo"><h3>Loading Game Information...</h3></div>
<div id="submissionarea"></div>
</body>
</html>
