<?php
require_once 'templates/page_setup.php';
$title = "Sign up";
$page_name = "signup";
  $users = readUsers();
  $user="";
  $hash="";
  $email="";
  $str="";
  if(isset($_POST['username'])){
    $user = strip_tags(filter_var($_POST['username'],FILTER_SANITIZE_STRING));
  }
  if(isset($_POST['password'])){
    $hash = password_hash(strip_tags(filter_var($_POST['password'],FILTER_SANITIZE_STRING)));
  }
  if(isset($_POST['email'])){
    $email = strip_tags (filter_var($_POST['email'],FILTER_SANITIZE_EMAIL));
  }
  if(!empty($user) and !empty($hash) and !empty($email)){
    if(addUserToTable($user,$hash,$email)){
      header("location: login.php");
      exit;
    }
    else{
      $str= '\tUsername taken';
    }
  }
?>

<?php include 'templates/header.php';?>
</head>
<!-- Start of page Body -->

<body>
  <div class="header">
    <h2>Register Now! </h2>
  </div>
  <div class="contents">
    <p>Enter your username and desired password:</p>
    <form action="signup.php" method="post">
Username: <input type="text" name = "username">
<?php if(!empty($str))  echo $str;?><br>
Password: <input type="password" name="password"><br>
Email:    <input type="text" name = "email"><br>
      <input type="submit"><br>
    </form>
<?php include 'templates/footer.php';?>

