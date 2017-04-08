<?php
require_once 'templates/page_setup.php';
$title = "Forgot Password";
$page_name = "forgotpassword";
  $users = User::readUsers();
  include 'templates/header.php';
  include 'templates/jumbotron.php';
  echo '
</head>
<body>
  <div class="header">
    <h2> Your password is being reset.</h2>
  </div>
        ';
  $str="";
  if(isset($_POST['forgot_password'])){
    $che = strip_tags(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL));
    if(User::checkEmail($users,$che)){
      $_SESSION['email'] = $che;
      $email = $che;
      $password = $_SESSION['randkey'];
      $pwrurl = "www.cs.colostate.edu/~mjrerle/p2/passwordreset.php?q=".$password;
      $user = $_POST['username'];
      $mailbody = "Hello $user, here is the link: ".$pwrurl;
      if(mail($email, "www.cs.colostate.edu/~mjrerle - Password Reset", $mailbody)){
        echo "Your password recovery key has been sent to your email address";
      }
      else{
        echo "Failed to send email";
      }
    }
    else{
      echo 'Email given does not match the one on record! Try again? <a href="forgot_password.php">Click here</a>';
    }
  }
  else {
    echo '
  <div class="contents">
    <p>Select your username and enter your email</p>
    <form action = "forgot_password.php" method = "post">
      <select name = "username">
         ';
    foreach ($users as $u) {
      $flag = ($u->username == $_SESSION['username']) ? 'selected' : '';
      echo "\t\t\t\t<option value=\"$u->username\" $flag > $u->username </option>\n";
    }
    echo '
      </select><br>
      Email Address: <input type = "text" name = "email"><br>
      <input type = "submit" name = "forgot_password" value ="Request Reset">';
    echo '<br>
    </form>
         ';
  }
  include 'templates/footer.php';
?>

