<?php
require("sessionstart.php");
if(!isset($_SESSION["username"])||!$_SESSION["Usertype"]=="Admin")
{
  header("Location: login.php");
}
?>
