<?php
require_once "templates/page_setup.php";
$title = "Products";
$page_name = "products";
include "templates/header.php";
$current_tab = "i_name";
$current_page=1;
$num_per_page=4;

$dbh = new Database();
$max_pages = ceil($dbh->getNumberOfIngredients()/$num_per_page);
$offset = $num_per_page * ($current_page-1);
$ingredients = $dbh->getIngredients();
include "templates/jumbotron.php";
?>
<div class = "container-fluid product-list" id="content" style ="">
  <div class = "row">
    <div class = "col-md-12">
      <div class="row">
<?php
if(isset($_SESSION['status']) and $_SESSION['status']=="Admin"){
  echo '<a href="ingredient_form.php?action=new" class = "btn bth-default btn-sm">Click here to add new ingredient. <span class ="glyphicon glyphicon-plus"></span></a><br>';
}
?>
<?php
foreach($ingredients as $i){
  $productID=$i->id;
  $price = $i->price;
  $detailsURL = htmlspecialchars($_SERVER['PHP_SELF']). '?action=view&id='.$productID;
  $imageURL = $i->imgURL;
  $reviewCount = $dbh->getNumberOfCommentsForIngredient($i);
  $name = $i->name;
  $description = $i->description;
  $rating = $dbh->averageRating($i);
  $stars = $dbh->getRatingStars($rating);
  echo '<div class = "col-sm-3 col-md-3 col-xs-3 product-listing">
          <div class="thumbnail">
            <a href="'.$detailsURL.'">
              <img src="assets/img/'.$imageURL.'" alt="thumbnail">
            </a>
            <div class = "caption">
              <h4 class = "pull-right">$'.$price.'</h4>
              <h4><a href="'.$detailsURL.'">'.$name.'</a>
              </h4>
              <p>'.$description.'</a></p>
            </div>
            <div class = "ratings">
              <p class="pull-right"> '.$reviewCount.' reviews</p>
              <p>'.$stars.'</p>
            </div>
          </div>
        </div>';
  }
?>
        </div>
      </div>
    </div>
  </div>

<?php include 'templates/footer.php';
