
<?php

require "functions.php";
    if(isset($_POST['register'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];

        // Call the register function and handle the response
        $response = register($email, $username, $password, $confirm_password);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="user.css">
   <title>Register page</title>
</head>
<body>
   
<form action="" method="POST">
      <h2>Register form</h2>
      <p class="info">
         Please fill out the form to create your account.
      </p>
 
      <label>Email</label>
      <input type="text" name="email" value="<?php echo @$_POST['email'] ?>">
      
      <label>Username</label>
      <input type="text" name="username" value="<?php echo @$_POST['username'] ?>">
   
      <label>Password</label>
      <input type="text" name="password" value="<?php echo @$_POST['password'] ?>">
 
      <label>Confirm Password</label>
      <input type="text" name="confirm-password" value="<?php echo @$_POST['confirm-password'] ?>">
   
      <button type="submit" name="register">Register</button>
      
      <p class="have-account">
         <a href="login.php">Already have an account?</a>
      </p>
      
      <p class="error"><?php echo @$response ?></p>		
   </form>
 
</body>
</html>