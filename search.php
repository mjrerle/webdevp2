<?php
  require_once "templates/page_setup.php";
  $title = "Search";
  $page_name = "search";
  include "templates/header.php";
  $current_tab = "comment_rating";
  $current_page = 1;
  $num_per_page = 25;
  include "templates/jumbotron.php";
?>
<main>
  <div class = "container">
    <div class = "row">
      <div class = "col-sm-3">
      </div>
      <div class = "col-sm-9">
        <div class = "header">
          <h2>Search for your favorite ingredient</h2>
        </div>
<?php

  if(isset($_GET['search']) and !empty($_GET['search']) ):
    $tab_urls = Utils::removeParameterFromUrl("b");
    $tab_urls = Utils::makeSureURLisQueryString($tab_urls);
?>
<?php
  $db = new Database();
  $query_term = strip_tags($_GET['search']);
  $num_of_results = $db->getNumberOfResults($query_term);
  $offset = $num_per_page*($current_page-1);
  $ingredients = $db->searchForResults($query_term);
  $max_pages = ceil($num_of_results/$num_per_page);
?>
        <div class = "pull-left" style = "padding:20px;">Showing search results for <b><?php echo strip_tags($_GET['search']);?></b> (total of <?php echo $num_of_results;?>)
        </div>
<?php
  if($num_of_results==0):
    echo "<p class = \"clear-all\" We did not find any results for the term <b>$query_term</b>";
  else:
?>
        <nav class = "pull-right">

        </nav>
        <table class="table table-condensed table-striped clear-all">
        <!-- defines the header of a table -->
        <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
        <?php
          foreach ($ingredients as $ingredient){ ?>
          <tr>
            <td><?php echo $ingredient->name ?></td>
            <td><?php echo $ingredient->price ?></td>
            <td><?php echo $ingredient->description ?></td>
          </tr>
        <?php
          }
        ?>
        </tbody>
       </table>
         <nav class="pull-right">

      </nav>
      <?php
        endif;
        endif;
      ?>
      </div>
    </div>
  </div>
<?php
  if(empty($_GET['search'])){
    echo '<b>You searched for nothing!</b>';
    echo '<p>Check out all of our <a href="products_listing.php">products</a> here!</p>';
  }
?>
</main>
<?php include "templates/footer.php";?>
