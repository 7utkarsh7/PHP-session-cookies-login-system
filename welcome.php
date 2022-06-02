<?php
// Initialize the session
session_start();
require_once "config.php";

$username = $email= $country= $state = $pincode ="";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}else{
    $id=$_SESSION["id"];

    $sql = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($link, $sql);

   if (mysqli_num_rows($result) > 0) {
   // output data of each row
     while($row = mysqli_fetch_assoc($result)) {
      $username= $row["username"];
      $email= $row["email"];
      $country= $row["country"];
      $state= $row["state"];
      $pincode=$row["pin"]. "<br>";
   }
   } else {
    echo "not found error";
  }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; margin-top:10%; }
        .table{
            width: 70%;
            margin-left:15%;
        }
    </style>
</head>
<body>
    <h1 class="my">Profile Info</h1>
    <table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">Username</th>
      <th scope="col">Email</th>
      <th scope="col">Country</th>
      <th scope="col">State</th>
      <th scope="col">Pincode</th>
    </tr>
  </thead>
  <tbody>
    <tr class="table-success">
      <th scope="row"><?php echo $username; ?></th>
      <th ><?php echo $email; ?></th>
      <th ><?php echo $country; ?></th>
      <th ><?php echo $state; ?></th>
      <th ><?php echo $pincode; ?></th>
    </tr>
  </tbody>
</table>
    <p> 
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
</body>
</html>