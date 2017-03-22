<?php include 'templates/header.php';?>
<?php include 'templates/jumbotron.php';?>
<div class="container">
<div class = "container form-signin">
<?php
  $msg='';                    
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $hash1 = "c963e367f63428189a6fa9cbd9e71a83";
    //mjrerle
    $hash2 = "3aaec86181ee6974b99d893b4c1eb5b5";
    //IFY_hortus
    $hash3 = "f82e20303393b2022146969d860ea0bf";
    //camja
    $store = md5($_POST['password']);
    $user=filter_var($_POST['username'],FILTER_SANITIZE_STRING);
    $auth= 'Correct Password!'."<br>".date('l jS \of F Y h:i:s A')."<br>Welcome ".$user;
      if ($user == 'mjrerle' && ($store==$hash1)){
      $_SESSION['valid'] = true;
      $_SESSION['startTime'] = time();
      $_SESSION['username'] = 'mjrerle';
      $msg=$auth; 
      }
      elseif($user == 'ct310' && $store==$hash2){
      $_SESSION['valid']=true;
      $_SESSION['startTime']=time();
      $_SESSION['username']='ct310';       
      $msg=$auth;
      }
      elseif($user == 'camja' && $store==$hash3){
      $_SESSION['valid']=true;
      $_SESSION['startTime']=time();
      $_SESSION['username']='camja';                
      $msg=$auth;
      }
      else {$msg = "Wrong username or Password";}
  }
  elseif (isset($_POST['username']) && !isset($_POST['password'])){
    $msg= "Please set password";
  }
  elseif (!isset($_POST['username']) && isset($_POST['password'])){
    $msg= "Please set username";
  }
  elseif (!empty($_SESSION['username'])){
    $msg= "You are logged in as ".$_SESSION['username'];
  }
  else{$msg="Welcome";}
?>

</div>

  <div class="row">

    <div class="col-md-4"></div>
    <div class="col-md-4">

      <div class="wrapper">
        <form action="login.php" method="post" name="Login_Form" class="form-signin">
          <h2 class= "form-signin-heading">Login</h2>
          <p><?php echo $msg;?></p>
	     	  <hr class="colorgraph"><br>
		      <input type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
		      <input type="password" class="form-control" name="password" placeholder="Password" required=""/>     		  	 
 			    <button class="btn btn-lg btn-primary btn-block"  name="submit" value="login" type="Submit">Login</button><br><br>  			
        </form>
        <a href="logout.php">Click here to logout.</a>      
      </div>
    <div class="col-md-4"></div>
  </div>
</div>
<?php include 'templates/footer.php';?>
