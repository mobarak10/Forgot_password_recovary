<!-- backend for login page -->
<?php
include("db.php");
if (isset($_POST['submit'])) {
  $user = $_POST['username'];
  $password = md5($_POST['password']);
  $query = "SELECT * FROM users WHERE email = '$user' && password = '$password'";
  $data = mysqli_query($con, $query);
  $total = mysqli_num_rows($data);

  if ($total == 1) {
    header('location:welcome.php');
  }else{
    echo "<script>alert('Password or Username is incorrect');</script>";
    echo "<script>window.open('login.php','_self')</script>";
  }
}


?>