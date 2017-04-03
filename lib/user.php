<?php
/**
 * An adaptation of the User used in last lecture.
 * @author jgruiz
 *
 */
require_once "config.php";

class User {
  public $first_name            = 'John';          /* Users first name */
  public $last_name             = 'Doe';           /* Users last name */
  public $username             = '';              /* User Name */
  public $hash                  = '';              /* Hash of password */
  public $status          = ''; //Expects New, Active, Banned

  public function __construct($first = "", $last = "",
       $username = "", $passwd = "", $status="New"){
    $this->first_name = $first;
    $this->last_name  = $last;
    $this->username  = $username;
    $this->hash = password_hash($passwd, PASSWORD_DEFAULT);
    $this->status = $status;
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
	  $users [$i ++] = new User ( 'testuser', '$2y$10$duu7O.7GM5dZp1LBtrGLm.eg.649dJKdhKHVtup8yqlny1flKQoNe', 'matterle@live.com' );
    $users [$i ++] = new User ( 'ct310', '$2a$10$heta6oei4CTS/cJqZ6HOquch7Oy.iUOuVXQrH/.tUoURV41/eaIXK', 'nspatil@colostate.edu' );
    User::writeUsers ( $users );
  }

  public static function writeUsers($users) {
    $fh = fopen(dirname(__FILE__) . '/users.tsv', 'w+') or die("Can't open file");
    fwrite($fh, $users[0]->headings()."\n");
    for ($i = 0; $i < count($users); $i++) {
      fwrite($fh, $users[$i]->contents()."\n");
    }
    fclose($fh);
  }

  public static function resetUserPassword($email, $newpwhash){
    $input = fopen('users.csv', 'r') or die ("Can't open file");
    $output = fopen('temp.csv', 'w+');
    while(false !== ($data = fgetcsv($input))){
      if($data[2] == $email){
        $data[1] = $newpwhash;
      }
      fputcsv($output,$data);
    }
    fclose($input);
    fclose($output);
    unlink('users.csv');
    rename('temp.csv','users.csv');
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
	 if (! file_exists(dirname(__FILE__).'/users.tsv')) { User::setupDefaultUsers(); }
    $contents = file_get_contents(dirname(__FILE__).'/users.tsv');
    $lines    = preg_split("/\r|\n/", $contents, -1, PREG_SPLIT_NO_EMPTY);
    $keys     = preg_split("/\t/", $lines[0]);
    $i        = 0;
    for ($j = 1; $j < count($lines); $j++) {
      $vals = preg_split("/\t/", $lines[$j]);
      if (count($vals) > 1) {
        $u = new User();
        for ($k = 0; $k < count($vals); $k++) {
          $u->$keys[$k] = $vals[$k];
        }
        $users[$i] = $u;
        $i++;
      }
    }
    return $users;
  }
  public static function loginRequired(){
    global $_SESSION;
    global $config;

    if(isset($_SESSION["username"])){
      $users = User::readUsers();
      foreach ($users as $user){
        if($user->username == $_SESSION["username"]){
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
