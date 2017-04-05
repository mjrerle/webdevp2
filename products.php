<?php
if(isset($_GET['action'])){
  $action = $_GET['action'];
  if($action=='view'){
    actionView();
  }
  elseif ($action=='review'){
    actionReview();
  }
  elseif($action=='list'){
    actionList();
  }
}
else{
  actionList();
}
function actionView(){
  if(isset($_GET['id'])){
    include 'product_page.php';
  }
  else{
    die("Error: No ID specified.");
  }
}
function actionList(){
  include 'products_listing.php';
}
function checkProductID(){
  $productID = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
  $options = array("options"=>array("min_range"=>0));

  if(filter_var($productID,FILTER_VALIDATE_INT)===0 or !filter_var($productID,FILTER_VALIDATE_INT, $options) === false){
    return $productID;
  }
  else{
    die("Invalid product ID");
  }
}
function actionReview(){
  if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_GET['id'])){
      require_once "create.php";
      if(!$dbh=setupProductConnection()) die;
      dropTableByName("ingredient");
      dropTableByName("comment");
      createTableIngredient();
      createTableComment();
      loadProductsIntoEmptyDatabase();

      $id = checkProductID();
      $ingredient = $dbh->getIngredientByID($id)->name;
      $ratingOkay=$submissionOkay=true;
      $options = FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP;
      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING, $options);
      // Filter and validate rating
      $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
      if(!filter_var($rating, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1, "max_range"=>5))) === false) {
      }
      else {
      // bad rating value
        $submissionOkay = false;
        $ratingOkay = false;
      }
      // Filter comments
      $options = FILTER_FLAG_ENCODE_AMP | FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW;
      $words = filter_var($_POST['words'], FILTER_SANITIZE_STRING, $options);
      if($submissionOkay===true){
        $reviewArray = array(
          "name" => $name,
          "rating"=> $rating,
          "words"=> $words,
          "id"=> $id,
          "ingredient"=>$ingredient
        );
        $dbh->insertComment($reviewArray);
      }
      include 'product_page.php';
    }
    else {
      die("Error: No ID specified.");
    }
  }
  else {
    die("Error: No post data submitted.");
  }
}
?>
