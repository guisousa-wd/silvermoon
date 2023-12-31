<?php require "functions.php" ?>
<?php 
   if(isset($_POST['register'])){
      $response = register($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm-password']);
   }
?>