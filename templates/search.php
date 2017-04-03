<?php
  require_once "page_setup.php";
  $title = "Search";
  $page_name = "search";
  include "header.php";
  $current_tab = "comment_rating";
  $current_page = 1;
  $num_per_page = 25;
  if(isset($_GET['b'])) $current_tab = strip_tags($_GET['b']);
  if(isset($_GET['q'])) $current_page = intval($_GET['q']);
?>
<main>
  <div class = "container">
    <div class = "row">
      <div class = "col-sm-3">
      </div>
      <div class = "col-sm-9">
        <div class = "header">
          <h2>Search</h2>
        </div>
<?php
  if(isset($_GET['search'])):
    $tab_urls = Utils::removeParameterFromUrl("b");
    $tab_urls = Utils::makeSureURLisQueryString($tab_urls);
?>
        <nav>
          <ul class = "nav nav-tabs">
            <li <?php echo getIsActive($current_tab, "comment_rating"); ?>><a href = "<?php echo $tab_urls;?>&b=comment_rating">By Name</a></li>
            <li <?php echo getIsActive($current_tab, "ingredient_name"); ?>><a href = "<?php echo $tab_urls;?>&b=ingredient_name">By Name</a></li>
            <li <?php echo getIsActive($current_tab, "ingredient_price"); ?>><a href = "<?php echo $tab_urls;?>&b=ingredient_price">By Name</a></li>
          </ul>
        </nav>
<?php
  $db = new Database();
  $query_term = strip_tags($_GET['search']);
  $num_of_results = $db->getNumberOfResults($query_term);
  $offset = $num_per_page*($current_page-1);
  $ingredients = $db->searchForResultsAndSort($query_term,$current_tab,$num_per_page,$offset);
  $max_pages = ceil($num_of_results/$num_per_page);
?>
        <div class = "pull-left" style = "padding:20px;">
          Showing search results for <b><?php echo strip_tags($_GET['search']);?></b> (total of <?php echo $num_of_results;?>)
        </div>
<?php
  if($num_of_results==0):
    echo "<p class = \"clear-all\" We did not find any results for the term <b>$query_term</b>";
  else:
?>
        <nav class = "pull-right">
<?php
  Utils::createPagination($current_page,$max_pages);
?>
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
          foreach ($ingredient as $ingredient){ ?>
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
        <?php
          Utils::createPagination($current_page, $max_pages);
        ?>
      </nav>
      <?php
        endif;
        endif;
      ?>
      </div>
    </div>
  </div>
</main>
<?php include "footer.php";?>
