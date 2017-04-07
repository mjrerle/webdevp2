<?php
require_once "templates/page_setup.php";
$page_name = "cart";
include "templates/header.php";
include "templates/jumbotron.php";
?>

<?php 
if (!isset($_SESSION['array']) and !isset($_GET['id'])):
	echo '<h3 align="center">Your Cart is Empty</h3>';
	echo '<p align="center"><a href="products.php">Continue Shopping</a></p>';
	require 'templates/footer.php';
	die();
endif;
?>

<?php 
function in_cart($ing){
	
}
?>

<?php 
function view_cart(){
	$row = $_SESSION['array']; 
	echo '<table id="mycart">';
	echo '<tr> 
            <th>Name</th> 
            <th>Price</th>
			<th>Quantity</th>
          </tr> ';

	foreach ($row as $ing){
		$price = number_format((float)$_SESSION['items'][$ing->id]['Total'], 2 , '.' , ''); // formatted to 2 decimals
		$quant = $_SESSION['items'][$ing->id]['Quantity']; //quantity
		//if ($quant <= 1){
			echo '<tr>'; 
			echo "<td>$ing->name</td>";
			echo "<td>$price</td>";
			echo "<td>$quant</td>"; 
			echo '</tr>';
		//}
		
	}
	echo '</table>';
}
?>

<?php 

$dbh = new Database(); 
$id = $_GET['id'];
if (!isset($_SESSION['array'])):
	$row = array(); //stores ingredients in cart
	$row[] = $dbh->getIngredientbyID($id);
	$_SESSION['array'] = $row;
	$_SESSION['items'] = array();
	$_SESSION['items'][$id] = array('Quantity' => 1, 'Total' => $row[0]->price);

else:
	$row = $_SESSION['array'];  
	if (isset($_SESSION['items'][$id])): //item exists already
		$_SESSION['items'][$id]['Quantity']++;
		$price = $dbh->getIngredientbyID($id)->price;
		$_SESSION['items'][$id]['Total'] += $price; 
	else:
		//create a new item
		$_SESSION['items'][$id] = array('Quantity' => 1, 'Total' => $dbh->getIngredientbyID($id)->price);
		$row[] = $dbh->getIngredientbyID($id); //add to cart
		$_SESSION['array'] = $row;
	endif;
	
endif;


?>

<div class="cart">
	<h2 align="center">My Cart</h2>
	<?php view_cart(); ?>
	<p align="center"><a href="products.php">Continue Shopping</a></p>
</div>

<?php require 'templates/footer.php';?>

