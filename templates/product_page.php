<?php
require_once "templates/page_setup.php";
$title = "";
$page_name = "";
include "templates/header.php";
$dbh = new Database();
$productID = checkProductID();
$ingredient = $dbh->getIngredientByID($productID);
$reviewArray = $dbh->getCommentsForIngredient($ingredient);
$reviewCount = $dbh->getNumberOfCommentsForIngredient($ingredient);
include 'templates/jumbotron.php';
?>
<div class = "container-fluid product-details">
  <div class="row">
    <div class="col-md-3" id = "productImgCol">
      <img class = "product-image" src = "<?php //echo 'assets/img/'.$ingredient->imgURL;?>" alt="product_image">
    </div>
    <div class = "col-md-3" id="productDetailsCol">
      <h3><?php echo $ingredient->name; ?></h3>
      <p><?php echo $ingredient->description;?></p>
      <?php echo $dbh->getRatingStars($dbh->averageRating($ingredient));?>
      <span class =""><a href="#reviewList"><?php echo $reviewCount;?> Reviews</a></span>
      <h4>$<?php echo $ingredient->price;?></h4> 
    </div>
    <div class = "col-md-3" id=productPurchaseCol>
    </div>
  </div>
  <hr>
    <div class = "row">
      <div class = "col-sm-6" id="reviewList">
        <h3>Reviews and Comments</h3>
          <?php if(!empty($_SESSION['valid'])){?>
          <?php if(isset($submissionOkay) and $submissionOkay ===true):?>
            <div class = "alert alert-success" id="formSuccess">
              <?php echo "Your review was submitted successfully";?>
            </div>
          <?php endif;?>
          <?php if(isset($submissionOkay) and $submissionOkay != true):?>
            <div class = "alert alert-danger" id = "formError">
              <?php echo "There was an error with your review ksubmission, please try again.";?>
            </div>
          <?php endif;?>
          <p><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#reviewForm">Review this product</button></p>
            <div id="reviewForm" class="collapse">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?action=review&id=$productID"; ?>">
                    <div class="form-group">
                        <label for="nameInput">Your name *</label>
                        <input type="text" name="name" class="form-control" id="nameInput" placeholder="Enter name" required="true">
                    </div>


                    <div class="form-group">
                        <label for="ratingInput">Your rating *</label><br>
                        <label class="radio-inline">
                            <input type="radio" name="rating" value="1" required="true">1
                        </label>
                        <label class="radio-inline" >
                            <input type="radio" name="rating" value="2" required="true">2
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="rating" value="3" required="true">3
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="rating" value="4" required="true">4
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="rating" value="5" required="true">5
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="commentsInput">Comments</label>
                        <textarea class="form-control" name="words" id="commentsInput" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <?php
            } else{
            ?>
            <p style="color:red;">You must be logged in to review!</p>
            <?php } ?>
<?php
  foreach($reviewArray as $r){
    echo '<hr>
            <div class = "review">
              <h4>Posted by <i>'.$r->name.'</i></h4>
              <p>'.$dbh->getRatingStars($r->rating).'</p>
              <p>'.$r->words.'</p>
            </div>';
  }
?>
            </div>
          </div>
        </div>
      <?php require 'templates/footer.php';?>