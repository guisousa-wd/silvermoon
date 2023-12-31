<?php 
require "config.php";
require "connection.php";

define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'silvermoon');

function dbConnect(){
    $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
    if($mysqli->connect_errno != 0){
       return FALSE;
    }else{
       return $mysqli;
    }
 }

 function register($email, $username, $password, $confirm_password){
    $mysqli = dbConnect();
   if($mysqli == false){
      return false;
   }
   $args = [
    "email" => $email,
    "username" => $username,
    "password" => $password,
    "confirm-password" => $confirm_password
 ];
 $args = array_map(function($value){
    return trim($value);
 }, $args);
 $email = trim($email);
 $username = trim($username);
 $password = trim($password);
 $confirm_password = trim($confirm_password);

 foreach ($args as $value) {
    if(empty($value)){
       return "All fields are required";
    }
 }

 foreach ($args as $value) {
    if(preg_match("/([<|>])/", $value)){
       return "<> characters are not allowed";
    }
 }

 $args = array_map(function($value){
    return htmlspecialchars($value);
 }, $args);

 if(!filter_var($args["email"], FILTER_VALIDATE_EMAIL)){
    return "Email is not valid";
 }

 if(mb_strlen($args["username"]) > 20){
    return "The username must be under 20 characters";
 }

 if(mb_strlen($args["password"]) > 20){
    return "The password must be under 20 characters";
 }

 $result_set = $mysqli->query("SELECT * FROM users");
 $data = array();
 while($row = $result_set->fetch_assoc()){
    array_push($data, $row);
 }

 foreach ($data as $value) {
    if($args["email"] == $value["email"]){
       return "Email already exists";
    }
 }

 foreach ($data as $value) {
    if($args["username"] == $value["username"]){
       return "Username already exists";
    }
 }

 $verification_code = createVerificationCode($data);
 if(!sendVerificationCode($args["email"], $verification_code)){
    return "Error sending verification code. Please try again";
 }

 $hashed_password = password_hash($args["password"], PASSWORD_DEFAULT);


 $stmt = $mysqli->prepare("INSERT INTO users(email, username, password, verification_code) VALUES(?,?,?,?)");
 $stmt->bind_param("sssi", $args["email"], $args["username"], $hashed_password, $verification_code);
 $stmt->execute();
 if($stmt->affected_rows != 1){
    return "An error occurred. Please try again";
 }else{
    $_SESSION['email'] = $args["email"];
    $_SESSION['verification-code'] = $verification_code;
    header("Location: auth.php");
    exit();
 }
} // Closing the register function.

function createVerificationCode($database_data){
    $str = "0123456789";
    $random = str_shuffle($str);
    $verification_code = substr($random, 0, 6);
    
    $codes = array_map(function($value) {
        return $value["verification_code"];
    }, $database_data);
    
    if(in_array($verification_code, $codes)){
       return createVerificationCode($database_data);
    }else{
       return $verification_code;
    }
 }

 function sendVerificationCode($email, $verification_code){
    $subject = "verification code";
    $body = "Verification code". "\r\n";
    $body .= $verification_code;
  
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: you@localhost.com \r\n"; 
    return mail($email, $subject, $body, $headers);
 }

 function verifyEmail($verification_code){
    $mysqli = dbConnect();
    if($mysqli == false){
       return false;
    }
  
    if(trim($verification_code) == ""){
       return "Please enter the 6 digits code";
    }
  
    if(!preg_match("/^\d{6}$/", $verification_code)){
       return "The string expects a 6 digits number";
    }
  
    $res = $mysqli->query("SELECT verification_code FROM users WHERE verification_code = '$verification_code'");
    if($res->num_rows != 1){
       return "Wrong verification code";
    }else{
       $update = $mysqli->query("UPDATE users SET email_status = 'verified' WHERE verification_code = '$verification_code'");
       if($mysqli->affected_rows != 1){
          return "something went wrong. Please try again";
       }else{
          header("Location: login.php");
          exit();
       }
    }
 }
 function login($username, $password){
    $mysqli = dbConnect();
    if(!$mysqli){
       return false;
    }

    $username = trim($username);
    $password = trim($password);

    if($username == "" || $password == ""){
        return "Both fields are required";
     }

     $sql = "SELECT * FROM users WHERE username = ?";
     $stmt = $mysqli->prepare($sql);
     $stmt->bind_param("s", $username);
     $stmt->execute();
     $result = $stmt->get_result();
     $data = $result->fetch_assoc();
     if($data == NULL){
        return "Wrong username or password";
     }

     if($data["email_status"] == "pending"){
        $_SESSION['email'] = $data["email"];
        $_SESSION['verification-code'] = $data["verification_code"];
   
        sendVerificationCode($data["email"], $data["verification_code"]);
        header("location: auth.php");
        exit();
     }

     if(password_verify($password, $data["password"]) == FALSE){
        return "Wrong username or password";
     }else{
        $_SESSION["user"] = $username;
        header("location: account.php");
        exit();
     }
  } // closing the login function.

  //PASSWORD RESET
  function passwordReset($email){
    $mysqli = dbConnect();
    if(!$mysqli){
       return false;
    }

    $email = trim($email);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "Email is not valid";
     }
     $stmt = $mysqli->prepare("SELECT email FROM users WHERE email = ?");
     $stmt->bind_param("s", $email);
     $stmt->execute();
     $result = $stmt->get_result();
     $data = $result->fetch_assoc();
     if($data == NULL){
        return "Email doesn't exist in the database";
     }

     $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz";
     $password_length = 7;
     $shuffled_str = str_shuffle($str);
     $new_pass = substr($shuffled_str, 0, $password_length);

     $subject = "Password recovery";
     $body = "You can log in with your new password". "\r\n";
     $body .= $new_pass;
     $headers = "MIME-Version: 1.0" . "\r\n";
     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
     $headers .= "From: Admin \r\n";
     $sent = mail($email, $subject, $body, $headers);

     if($sent == FALSE){
        return "Email not sent. Please try again";
     }

     else{
        $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
   
        $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();
        if($stmt->affected_rows != 1){
           return "There was a connection error, please try again.";
        }else{
           return "success";
        }			
     }
  } // closing the passwordReset function.

  //LOGOUT

  function logout(){
    session_destroy();
    header("location: login.php");
    exit();
 }
