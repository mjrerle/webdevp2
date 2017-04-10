<?php
require_once "templates/page_setup.php";
$page_name = "forgot_pass";
include "templates/header.php";
include "templates/jumbotron.php";
$users = User::readUsers(); //get users from users.csv
?>

<?php
  if (isset($_POST['email'])){
	  $url = 'https://www.cs.colostate.edu/~mjrerle/p2/passwordreset1.php'; //needs to change based on who's account is hosting
	  $to  = strip_tags($_POST['email']);
	  $subject = 'Reset Password';
	  $_GET['key'] = str_shuffle('abcdefghijklmnopqrstuvwxyzabitrl');
	  $_SESSION['token'] = $_GET['key'];
	  $_SESSION['email'] = strip_tags($_POST['email']);
	  $message = 'Please click URL to reset password: ' . $url . '?key=' . $_SESSION['token'];
	  mail($to,$subject,$message);
  }

?>

<div class="select_email">
	<h3>Enter your email please:</h3>
	<p>We will send an email to you. Please follow the link within to reset your password.</p>
	<form action="#" method="post">
		<input type="text" name="email"/>
		<input type="submit" /><br/><br/>
    </form>
</div>


<?php require 'templates/footer.php';?>
