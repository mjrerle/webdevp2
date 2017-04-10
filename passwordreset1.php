<?php
require_once "templates/page_setup.php";
$page_name = "forgot_pass";
include "templates/header.php";
include "templates/jumbotron.php";
?>


<?php
  if (isset($_GET['key']) && $_GET['key'] != $_SESSION['token']){
	echo '<h2 align="center">You arrived here by accident, please return to <a href="index.php">home page</a>.</h2>';
	include 'templates/footer.php';
	die();
  }

  function resetUserPassword($email, $newpwhash){
    $input = fopen('lib/users.csv', 'r') or die ("Can't open file");
    $output = fopen('lib/temp.csv', 'w+');
    while(!feof($input)){
		$line = fgets($input, 2048);
		$data = str_getcsv($line, ",");
        if(isset($data[3]) and $data[3] == $email){
          $data[1] = $newpwhash;
        }
      fputcsv($output,$data,",");
    }
    fclose($input);
    fclose($output);
    unlink('lib/users.csv');
    rename('lib/temp.csv','lib/users.csv');
  }
?>



<div class="contents">
	<?php
      if (isset($_POST['new']) && isset($_POST['confirm'])){
	    if ($_POST['new'] != $_POST['confirm']){
			echo '<h2 align="center">The entered passwords are not the same</h2>';
		}
		else{
			$new_pass = password_hash(strip_tags($_POST['new']), PASSWORD_BCRYPT);
			$email = $_SESSION['email'];
			resetUserPassword($email, $new_pass);
			echo '<h2 align="center">Password changed! <a href="login.php">Login</a></h2>';
		}
      }
    ?>
	  <form action="#" method="post">
        New Password: <input type="password" name="new"><br/><br/>
	    Confirm Password: <input type="password" name="confirm"><br/><br/>
	    <input type="submit" value="Submit">
      </form>
</div>

<?php require 'templates/footer.php';?>
