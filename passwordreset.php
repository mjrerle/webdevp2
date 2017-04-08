<?php
require_once 'templates/page_setup.php';
$title = "Password Reset";
$page_name = "passwordreset";

  if(isset($_POST['reset_pass_form'])){
    $email = $_SESSION['email'];
    $password="";
    $confirmpassword="";
    if(isset($_POST['newpassword']))        $password = strip_tags(filter_var(($_POST['newpassword']),FILTER_SANITIZE_STRING));
    if(isset($_POST['confirmpassword']))   $confirmpassword = strip_tags(filter_var($_POST['confirmpassword'],FILTER_SANITIZE_STRING));
    $hash = $_POST['q'];
    $resetkey = $_SESSION['randkey'];
    if($resetkey == $hash){
      if($password == $confirmpassword){
        $newpwhash = password_hash($password);
        User::resetUserPassword($email,$newpwhash);
        //update user entry
        header("location: https://$host$uri/index.php");
      }
    }
  }
  include 'templates/header.php';
  include 'templates/jumbotron.php';
?>

</head>
<body>
  <div class="contents">

<?php echo '
  <form action "passwordreset.php" method = "post">
    New Password: <input type = "password" name = "newpassword"><br>
    Confirm Password: <input type = "password" name = "confirmpassword"><br>
    <input type = "hidden" name = "q" value = "';
    if(isset($_GET['q'])){
      echo $_GET['q'];
    }
    echo '" ><input type = "submit" name = "reset_pass_form" value = "Reset Password">
  </form>';
?>
  </div>
<?php include 'templates/footer.php';?>
