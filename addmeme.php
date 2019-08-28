<?php require("adminauth.php")?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<script type="text/javascript" src="removewatermark.js"></script>
<title>Add Meme</title>
</head>
<body>
<h1>Open Captioning</h1>
  <?php
  require("db.php");
  $target_dir = "captiongameimages/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"]))
  {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
      } else {
          echo "File is not an image.";
          $uploadOk = 0;
      }
  }
  if ($uploadOk == 0)
  {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  }
  else
  {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";
          $target_file=str_replace("captiongameimages/","",$target_file);
          $name=$target_file;
          $name=str_replace("-"," ",$name);
          $name=str_replace(".jpg","",$name);
          $name=str_replace(".png","",$name);
          $query="SELECT * FROM Memes";
          $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
          $rowcount=mysqli_num_rows($result);
          $nextrow=$rowcount+1;
          $query="INSERT INTO Memes(ID,Name,Location)"."VALUES('$nextrow','$name','$target_file')";
          $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
          $categories=array("Classic","Anime/Gaming","Modern","TV/Movie","Other");
          for($i=0;$i<sizeof($categories);$i++)
          {
            $curcategory=$categories[$i];
            if(isset($_POST[$curcategory])&&$_POST[$curcategory])
            {
              $curcategorytext=str_replace("+"," ",$curcategory);
              $query=sprintf("INSERT INTO MemeCategory(Name,Category)"
              ."VALUES('%s','%s')",$name,$curcategorytext);
              $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
              print("<p>".$name." set as ".$curcategorytext." meme</p>");
            }
          }
      }
      else
      {
          echo "Sorry, there was an error uploading your file.";
      }

  }
  print("<p><a href='addmemeform.php'>Upload Another Meme</a></p>");
  print("<p><a href='mainmenu.php'>Return to Main Menu</a></p>");
  ?>
</body>
</html>
