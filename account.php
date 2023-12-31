<?php require "functions.php" ?>
<?php 
   if(!isset($_SESSION['user'])){
      header("Location: login.php");
      exit();
   }
   if(isset($_GET['logout'])){
      logout();
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
   <title>User account</title>
</head>
<body>
 
   <div class="page">
      <div class="top-bar">
         <h2>Welcome <?php echo @$_SESSION['user'] ?></h2>
         <a href="?logout">Logout</a>
      </div>
   </div>	
 
</body>
</html>	