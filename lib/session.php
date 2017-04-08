<?php
require_once "config.php";
require_once "lib/ingredient.php";
session_name($config->session_name);
session_start();
function randomString(){
  $str = "";
  $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
  $max = count($characters)-1;
  for($i = 0; $i<32;$i++){
    $rand = mt_rand(0,$max);
    $str .=$characters[$rand];
  }
  return $str;
}
if(! isset($_SESSION['randkey'])){
  $_SESSION['randkey'] = randomString();
}
if (! isset ( $_SESSION ['startTime'] )) {
  $_SESSION ['startTime'] = time ();
}
if (! isset ( $_SESSION ['username'] )) {
  $_SESSION ['username'] = "Guest";
}
$host = $_SERVER ['HTTP_HOST'];
$uri = rtrim ( dirname ( $_SERVER ['PHP_SELF'] ), '/\\' );
