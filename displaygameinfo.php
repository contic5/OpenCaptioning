<?php
require("sessionstart.php");
require("db.php");
$gameinfo="";
$answers=[];
$username=$_SESSION["username"];
$code=$_SESSION["code"];
$query="SELECT * FROM Games WHERE Code='$code'";
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$rowcount=mysqli_num_rows($result);
if($rowcount==0)
{
  $_SESSION["code"]="";
  header("Location: mainmenu.php");
}
if($code!="") //in a real game
{
  $visible=false;
  print("<h2>Game Code: $code</h2>");
  $query="SELECT * FROM Games WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $curimagelocation="";
  $curimagename="";
  while($row=mysqli_fetch_assoc($result))
  {
    $curimagelocation=$row["ImageLocation"];
    $curimagename=$row["ImageName"];
    if($row["AnswersVisible"]==1)
    {
      $visible=true;
    }
    else
    {
      $visible=false;
    }
  }
  print("<h3>$curimagename</h3>");
  print("<p><img src='captiongameimages/".$curimagelocation."'></img></p>");
  $query="SELECT * FROM Games WHERE Code='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $host='';
  while($row=mysqli_fetch_assoc($result))
  {
    $host=$row["Host"];
  }
  $query="SELECT * FROM Players WHERE CurrentGame='$code'";
  $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
  $playercount=mysqli_num_rows($result);
  $playeron=0;
  print("<h3>Answers</h3>");
  while($row=mysqli_fetch_assoc($result))
  {
    print("<div>");
    print("<p>Player ".($playeron+1).": ");
    print($row["Username"]."</p>");
    print("<p>Score: ".$row["Score"]."</p>");
    $answer=str_replace("&#8217","'",$row["Answer"]);
    array_push($answers,$answer);
    if($answer=="")
    {
      print("<div class='answer' id='p".$playeron."'>Answer not yet submitted</div><br>");
    }
    else if(!$visible)
    {
      print("<div class='answer' id='p".$playeron."'>Answers are still hidden.</div><br>");
    }
    else
    {
      print("<div class='answer' id='p".$playeron."'>".$answer."</div><br>");
    }
    if($username==$host)
    {
      print("<table><tbody>");
      print("<tr><td colspan='3'>Change Score</td></tr>");
      print("<tr>");
      print("<td>");
      print("<form method='post'>");
      print("<input type='hidden' name='changescoreplayer' value='".$row["Username"]."'>");
      print("<input type='hidden' name='amount' value='1'>");
      print("<button type='submit'>+1</button>");
      print("</form>");
      print("</td>");
      print("<td>");
      print("<form method='post'>");
      print("<input type='hidden' name='changescoreplayer' value='".$row["Username"]."'>");
      print("<input type='hidden' name='amount' value='-1'>");
      print("<button type='submit'>-1</button>");
      print("</form>");
      print("</td>");
      print("<td>");
      print("<form method='post'>");
      print("<input type='hidden' name='changescoreplayer' value='".$row["Username"]."'>");
      print("<input type='hidden' name='amount' value='0'>");
      print("<button type='submit'>Reset</button>");
      print("</form>");
      print("</td>");
      print("</tr></tbody></table>");
    }
    print("</div>");
    $playeron+=1;
  }
}
?>
