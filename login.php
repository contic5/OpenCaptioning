<?php
require("sessionstart.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<script type="text/javascript" src="removewatermark.js"></script>
<title>Open Captioning Login</title>
</head>
<body>
<?php
require("db.php");
if(isset($_POST["username"])&&isset($_POST["password"]))
{
  $username=$_POST["username"];
  $password=$_POST["password"];
  $hashedpassword=md5($password);

  //$stmt = $db->prepare('SELECT Username,Password FROM UserTable');

  $query=sprintf("SELECT * FROM Players WHERE Username='%s' AND Password='%s'",$username,$hashedpassword);
  $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
  $rows = mysqli_num_rows($result);
  print("<p>".$rows."</p>");
  if($rows==1)
  {
    $_SESSION["username"]=$username;
    $_SESSION["password"]=$password;
    while($row=mysqli_fetch_assoc($result))
    {
      $_SESSION["Usertype"]=$row["Usertype"];
    }
    header("Location: mainmenu.php");
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
<h1>Open Captioning</h1>
<h2>Login</h2>
<form method="post">
<p>Username:<input name="username" type="text"></p>
<p>Password:<input name="password" type="password"></p>
<p><button onclick="submit">Log In</button></p>
<p><a href="createaccount.php">Create Account</a></p>
</form>
</body>
</html>
