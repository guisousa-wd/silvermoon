<?php require "functions.php" ?>
<?php 
   if(isset($_POST['login'])){
      $response = login($_POST['username'], $_POST['password']);
   }
?>
	<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="user.css">
   <title>Login page</title>
</head>
<body>
   <!-- Login form -->
   <form action="" method="post" autocomplete="off">
      <h2>Login form</h2>
      <p class="info">
         Please enter your username and password to log-in.
      </p>
 
      <label>Username</label>
      <input type="text" name="username" value="<?php echo @$_POST['username'] ?>">
   
      <label>Password</label>
      <input type="text" name="password" value="<?php echo @$_POST['password'] ?>">
   
      <button type="submit" name="login">Login</button>
      
      <p class="forgot-password">
         <a href="forgot-password.php">Forgot your password?</a>
      </p>
      <p class="create-account">
         <a href="register.php">Create an account</a>
      </p>
      <p class="error"><?php echo @$response ?></p>		
   </form>
</body>
</html>	