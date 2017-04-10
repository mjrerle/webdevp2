<?php
require_once 'templates/page_setup.php';
$title = "Login";
$page_name = "login";
$stm;
if(isset($_POST['username']) and isset($_POST['password'])){
  $new=strip_tags(filter_var($_POST['username'],FILTER_SANITIZE_STRING));
  $epw=strip_tags(filter_var($_POST['password'],FILTER_SANITIZE_STRING));
  if($user=User::getUser($new, $epw)){
    $_SESSION['startTime'] = time();
    $_SESSION['username'] = $user->username;
    $_SESSION['valid'] = true;
    $_SESSION['status'] = $user->status;
    header( "location: https://$host$uri/index.php");
  }
  else{
    $stm = "Incorrect username or password";
  }
}
?>

<?php include 'templates/header.php';?>

<?php include 'templates/jumbotron.php';?>
<div class="container">
<div class = "container form-signin">

</div>

  <div class="row">

    <div class="col-md-4"></div>
    <div class="col-md-4">

      <div class="wrapper">
        <form action="login.php" method="post" name="Login_Form" class="form-signin">
          <h2 class= "form-signin-heading">Login</h2>
	     	  <hr class="colorgraph"><br>
		      <input type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
		      <input type="password" class="form-control" name="password" placeholder="Password" required=""/>
 			    <button class="btn btn-lg btn-primary btn-block"  name="submit" value="login" type="Submit">Login</button><br><br>
        </form>
<?php
if(isset($stm)){
  echo '<p style = "color:red;">'.$stm.'</p>';
}
?>
        <a href="logout.php">Click here to logout.</a><br>
<?php
if(isset($_SESSION['valid'])){
?>
        <a href="forgot_password1.php">Forgot password? Click here to reset.</a><br>
<?php
}
?>
      </div>
    <div class="col-md-4"></div>
  </div>
</div>
<?php include 'templates/footer.php';?>
