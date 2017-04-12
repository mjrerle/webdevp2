<?php
require_once "templates/page_setup.php";
$page_name = "cart";
include "templates/header.php";
include "templates/jumbotron.php";
$dbh = new Database();
?>

<?php
function total (){
	$total = 0.0;
	$row = $_SESSION['array'];

	foreach ($row as $ing){
		$total += $_SESSION['items'][$ing->id]['Total'];
	}

	return $total;
}
?>

<?php
function view_cart(){
	$row = $_SESSION['array'];
	echo '<table class="table">';
	echo '<tr>
            <th>Name</th>
            <th>Price</th>
			<th>Quantity</th>
          </tr> ';
	foreach ($row as $ing){
		$price = number_format((float)$_SESSION['items'][$ing->id]['Total'], 2 , '.' , ''); // formatted to 2 decimals
		$quant = $_SESSION['items'][$ing->id]['Quantity']; //quantity
			echo '<tr>';
			echo "<td>$ing->name</td>";
			echo "<td>$price</td>";
			echo "<td>$quant</td>";
			echo "<td><a href=\"cart.php?remove=yes&id=$ing->id\">Remove from Cart</a></td>";
			echo '</tr>';

	}
	echo '</table>';
}
function remove_item($id){
	foreach($_SESSION['array'] as $itemsKey => $items){
		foreach($items as $valueKey => $value){
			if($valueKey == 'id' && $value == $id){
				//delete this particular object from the $array
				unset($_SESSION['array'][$itemsKey]);
			}
		}
	}
	if (empty($_SESSION['array']))
		unset($_SESSION['array']);
}
?>

<?php
if (isset($_GET['remove'])):
	$id = $_GET['id'];
	$quant = $_SESSION['items'][$id]['Quantity'];
	if ($quant <= 1):
		unset($_SESSION['items'][$id]); //remove
		remove_item($id);//unset($_SESSION['array']);
		unset($_GET['id']);
	else:
		$_SESSION['items'][$id]['Quantity']--; //remove only 1
		$_SESSION['items'][$id]['Total'] -= $dbh->getIngredientbyID($id)->price;
	endif;
	header('Location: cart.php');
	die();
endif;
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
if (isset($_GET['id'])):
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
endif;
?>

<div class="cart">
	<h2 align="center">My Cart</h2>
	<?php view_cart(); ?>
	<h3 align="center">Your Total is: $<?php echo number_format((float)total(), 2 , '.' , '');?></h3>
	<p align="center"><a href="checkout.php">Checkout</a></p>
	<p align="center"><a href="products.php">Continue Shopping</a></p>
</div>

<?php require 'templates/footer.php';?>
