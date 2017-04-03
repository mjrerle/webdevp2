<?php
require 'templates/header.php';
require_once 'includes/ingredients.php';
require_once 'includes/reviews.php';
$productID = checkProductID();
$ingredientStore = new IngredientStore('data/ingredients.xml');
$reviewStore = new ReviewStore('data/comments.xml');
$ingredient = $ingredientStore->getIngredient($productID);
$reviewArray = $reviewStore->getProductReviews($productID);
$reviewCount = count($reviewArray);
include 'templates/jumbotron.php';
?>
<div class="container-fluid product-details">
    <div class="row">
        <div class="col-md-3" id="productImgCol">
            <img class="product-image" src="<?php echo $ingredient->imgURL; ?>" alt="product image">
        </div>
        <div class="col-md-3" id="productDetailsCol">
            <h3><?php echo $ingredient->name; ?></h3>
            <p><?php echo $ingredient->description; ?></p>
            <?php echo $reviewStore->getRatingStars($reviewStore->getProductAvgRating($ingredient['id'])); ?>
            <span class=""><a href="#reviewList"><?php echo $reviewCount; ?> Reviews</a></span>
            <h4>$<?php echo $ingredient->price; ?></h4>
        </div>
        <div class="col-md-3" id="productPurchaseCol">

        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6" id="reviewList">
            <h3>Reviews and Comments</h3>
            <?php if(!empty($_SESSION['valid'])){?>

            <?php if(isset($submissionOkay) and $submissionOkay === true): ?>
                <div class="alert alert-success" id="formSuccess">
                    <?php echo "Your review was submitted successfully."; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($submissionOkay) and $submissionOkay != true): ?>
                <div class="alert alert-danger" id="formError">
                    <?php echo "There was an error with your review submission, please try again."; ?>
                </div>
            <?php endif; ?>

            <p><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#reviewForm">Review this product</button></p>
            <div id="reviewForm" class="collapse">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?action=review&id=$productID"; ?>">
                    <div class="form-group">
                        <label for="nameInput">Your name *</label>
                        <input type="text" name="name" class="form-control" id="nameInput" placeholder="Enter name" required="true">
                    </div>
                    <div class="form-group">
                        <label for="titleInput">Review Title *</label>
                        <input type="text" name="title" class="form-control" id="titleInput" placeholder="Enter a title for your review" required="true">
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
                        <textarea class="form-control" name="comment" id="commentsInput" rows="4"></textarea>
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
            foreach ($reviewArray as $review) {
                echo   '<hr>
            <div class="review">
            <h4>'. $review->title .'</h4>
            <h5> Posted by <i>' . $review->author . '</i> on ' . $review->date . '</h5>
            <p>' . $reviewStore->getRatingStars($review->rating) . '</p>
            <p>' . $review->comment . '</p>
            </div>';
            }
            ?>
        </div>
    </div>
</div>
<?php require 'templates/footer.php'; ?>
