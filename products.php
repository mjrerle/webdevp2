<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if($action == 'view') {
        actionView();
    }
    elseif ($action == 'review') {
        actionReview();
    }
    elseif ($action == 'list') {
        actionList();
    }
}
else {
    actionList();
}
function actionView()
{
    if (isset($_GET['id'])) {
        include 'templates/product_page.php';
    } else {
        die("Error: No ID specified.");
    }
}
function actionList()
{
    include 'templates/products_listing.php';
}
function actionReview()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_GET['id'])) {
            require_once 'includes/reviews.php';
            $reviewStore = new ReviewStore('data/comments.xml');
            $ratingOkay = $submissionOkay = true;
            $id = checkProductID();
            // Filter and validate email address
            $options = FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP;
            $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING, $options);
            // Filter name
            $options = FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_AMP;
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING, $options);
            // Filter and validate rating
            $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
            if(!filter_var($rating, FILTER_VALIDATE_INT, array("options" => array("min_range"=>1, "max_range"=>5))) === false) {
                // rating okay
            } else {
                // bad rating value
                $submissionOkay = false;
                $ratingOkay = false;
            }
            // Filter comments
            $options = FILTER_FLAG_ENCODE_AMP | FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW;
            $comments = filter_var($_POST['comment'], FILTER_SANITIZE_STRING, $options);
            if ($submissionOkay === true) {
                $reviewArray = array(
                    "productID"=> $id,
                    "title" => $title,
                    "author" => $name,
                    "rating" => $rating,
                    "comment" => $comments,
                    "date" => date("m/d/Y")
            );
                $reviewStore->addReview($reviewArray);
            }
            include 'templates/product_page.php';
        } else {
            die("Error: No ID specified.");
        }
    }
    else {
        die('ERROR: No post data submitted');
    }
}
function checkProductID() {
    $productID = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $options = array( "options"=>array("min_range"=>0));
    if(filter_var($productID, FILTER_VALIDATE_INT) === 0 or !filter_var($productID, FILTER_VALIDATE_INT, $options) === false) {
        return $productID;
    } else {
        die("Invalid product ID");
    }
}

