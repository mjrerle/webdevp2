<?php
/**
 * An adaptation of the User used in last lecture.
 * @author jgruiz
 *
 */
require_once "config.php";
require_once "assets/user/passwordLib.php";

class User {
  public $username             = '';              /* User Name */
  public $hash                  = '';              /* Hash of password */
  public $status          = ''; //Expects New, Active, Banned
  public $email = "";

  public function __construct($username = "", $passwd = "", $status="New", $email=""){
    $this->username  = $username;
    $this->hash = password_hash($passwd, PASSWORD_DEFAULT);
    $this->status = $status;
    $this->email =$email;
  }
  /* This function provides a complete tab delimeted dump of the contents/values of an object */
  public function contents() {
    $vals = array_values(get_object_vars($this));
    return( array_reduce($vals, create_function('$a,$b',
        'return is_null($a) ? "$b" : "$a"."\t"."$b";')));
  }
  /* Companion to contents, dumps heading/member names in tab delimeted format */
  public function headings() {
    $vals = array_keys(get_object_vars($this));
    return( array_reduce($vals,
        create_function('$a,$b','return is_null($a) ? "$b" : "$a"."\t"."$b";')));
  }
  public static function setupDefaultUsers() {
	  $users = array ();
  	$i = 0;
	  $users [$i ++] = new User ( 'test', 'fish', 'Customer', 'matterle@live.com' );
	  $users [$i ++] = new User ( 'mjrerle', 'mjrerle', 'Admin', 'matterle@live.com' );
    $users [$i ++] = new User ( 'ct310', 'A835E0', 'Admin', 'ct310@cs.colostate.edu' );
    $users [$i ++] = new User ( 'fred', '3B23E6', 'Customer', 'ct310@cs.colostate.edu' );
    $users[$i ++] = new User('tjnolan', 'lukifer', 'Admin', 'tjnolan@rams.colostate.edu');
    User::writeUsers ( $users );
  }

  public static function writeUsers($users) {
    $fh=fopen('lib/users.csv', 'w+') or die("Can't open file");
    fputcsv($fh, array_keys(get_object_vars($users[0])));
    for($i=0;$i<count($users);$i++){
      fputcsv($fh,get_object_vars($users[$i]));
    }
    fclose($fh);
  }


  public static function resetUserPassword($email, $newpwhash){
    $input = fopen('lib/users.csv', 'r') or die ("Can't open file");
    $output = fopen('lib/temp.csv', 'w+');
    while(false !== ($data = fgetcsv($input))){
      if($data[2] == $email){
        $data[1] = $newpwhash;
      }
      fputcsv($output,$data);
    }
    fclose($input);
    fclose($output);
    unlink('lib/users.csv');
    rename('lib/temp.csv','lib/users.csv');
  }

  public static function checkEmail($users, $email){
    foreach($users as $u){
      if($u->email == $email)
        return true;
    }
        return false;
  }

  public static function addUserToTable($username, $password, $email){
    $userExists=false;
    $users = readUsers();
    for($i=0; $i<count($users);$i++)  if($users[$i]->username == $username) $userExists = true;
    if(!$userExists){
      array_push($users,makeNewUser($username,$password,$email));
      writeUsers($users);
      return true;
    }
    return false;
  }

public static function readUsers() {
  if(!file_exists('lib/users.csv')){
    User::setupDefaultUsers();
  }
  $users = array();
  $fh = fopen('lib/users.csv', 'r') or die("Can't open file");
  $keys = fgetcsv($fh);
  while(($vals=fgetcsv($fh))!=false){
    if(count($vals)>1){
      $u = new User();
      for($k=0;$k<count($vals);$k++){
        $u->$keys[$k]=$vals[$k];
      }
      $users[] = $u;
    }
  }
  fclose($fh);
  return $users;
}

  public static function loginRequired(){
    global $_SESSION;
    global $config;

    if(isset($_SESSION['username'])){
      $users = User::readUsers();
      foreach ($users as $user){
        if($user->username == $_SESSION['username']){
          if($user->status != "Banned"){
            return;
          }else{
            header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
            exit();
          }
        }
      }
    }
    $_SESSION['redirect'] = $_SERVER["REQUEST_URI"];
    //If we got here then we need to log in
    header("Location: " . $config->base_url . "/login.php");
    exit();
  }
  public static function adminLoginRequired(){
    global $_SESSION;

    if(isset($_SESSION['username']) and ($_SESSION['username']!="Guest")){
      $users = User::readUsers();
      foreach ($users as $user){
        if($user->username == $_SESSION['username']){
          if($user->status == "Admin"){
            return true;
          }
          else
            return false;
        }
      }
    }
    return false;
  }
  public static function customerLoginRequired(){
    global $_SESSION;
    if(isset($_SESSION['username']) and $_SESSION['username']!="Guest"){
      return true;
    }
    return false;
  }
  public static function userHashByName($users, $user) {
	  $res = '';
  	foreach ( $users as $u ) {
	  	if ($u->username == $user) {
		  	$res = $u->hash;
  		}
	  }
  	return $res;
  }
 public static function getUser($username, $password){
    $users = User::readUsers();
    foreach($users as $user){
      if($user->username == $username){
        if(password_verify($password, $user->hash)){

          return $user;
        }else{
          //We could just keep going but might as well
          //return once we know that the passwd is wrong
          return null;
        }
      }
    }
    return null;
 }
}
?>

