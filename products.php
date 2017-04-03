<?php
require_once "templates/page_setup.php";
$title = "Products";
$page_name = "products";
include "templates/header.php";
include "create.php";
$current_tab = "i_name";
$current_page=1;
$num_per_page =4;
if(!$dbh = setupProductConnection())  die;
dropTableByName("ingredient");
dropTableByName("comment");
createTableIngredient();
createTableComment();

loadProductsIntoEmptyDatabase();
$max_pages = ceil($dbh->getNumberOfIngredients()/$num_per_page);
$offset = $num_per_page * ($current_page-1);
$ingredients = $dbh->getIngredientsByField($current_tab, $num_per_page, $offset);
$comments = $dbh->getComments();
?>
<main>
  <div>
<?php
foreach($ingredients as $i){
  echo $i->name.'<br>';
}
foreach($comments as $c){
  echo $c->name.'<br>';
}
?>
  </div>
</main>

<?php include 'templates/footer.php';
