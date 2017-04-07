<?php
require_once "templates/page_setup.php";
$page_name = "checkout";
include "templates/header.php";
include "templates/jumbotron.php";
?>

<?php 
function total (){
	$total = 0.0;
	foreach($_SESSION['array'] as $itemsKey => $items){
		foreach($items as $valueKey => $value){
			if($valueKey == 'price'){
				$total += $value;
			} 
		}
	} 
	return $total;
}
?>

<div class="checkout">
	<h1 align="center">Your Total is $<?php echo total();?></h1>
	<h4 align="center">Checkout</h4>
</div>

<?php require 'templates/footer.php';?>
