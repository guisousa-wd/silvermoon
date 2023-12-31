<?php require "functions.php" ?>
<?php 
   if(isset($_POST['send-email'])){
      $response = passwordReset($_POST['email']);
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
   <title>Password reset</title>
</head>
<body>
   <!-- Forgot password form goes here -->

   <form action="" method="post">
      <h2>Password reset</h2>
      <p class="info">
         Please enter your email so we can send you a new password.
      </p>
 
      <label>Email</label>
      <input type="text" name="email" value="<?php echo @$_POST['email'] ?>">
      
      <button type="submit" name="send-email">Send</button>
 
      <?php 
         if(@$response == "success"){
            ?>
               <p class="success">Please go to your email account and copy your new password.</p>
            <?php
         }else{
            ?>
               <p class="error"><?php echo @$response; ?></p>
            <?php
         }
      ?>		
 
      <p class="forgot-password">
         <a href="login.php">Back to login page</a>
      </p>
   </form>
</body>
</html>	