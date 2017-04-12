<?php
require_once 'templates/page_setup.php';
$ingredient;
$ing;
$id;
$filename;
$idFound=false;
$db = new Database();
$max_file_size=1000000;
if(isset($_GET['action']) and isset($_SESSION['status']) and $_SESSION['status'] =="Admin"){
  if(isset($_GET['id'])){

    $idFound=true;
    $id = intval($_GET['id']);
    $ingredient = $db->getIngredientByID($id);
    if(strip_tags($_GET['action'])=="delete"){
      require_once 'data/list.php';
      $coms = $db->getCommentsForIngredient($ingredient);
      foreach($coms as $c){
        $db->deleteComment($c);
        deleteCommentFromFile($c);
      }

      $db->deleteIngredient($ingredient);
      deleteIngredientFromFile($ingredient);

      header('location: products.php');
    }
  }

}

else{
  header("location: index.php");
}
if ($_FILES && isset ( $_FILES ["image"] )) {
  if ($_FILES ["image"] ["error"] == UPLOAD_ERR_OK) {
    if ($_FILES ["image"] ["size"] > $max_file_size) {
      $error_msg = "File is too large.";
    } else {
      $ext = parseFileSuffix ( $_FILES ['image'] ['type'] );
      if ($ext == '') {
        $error_msg = "Unknown file type";
      } else {
        // Let database save assign unique integer id.

        $fid = $db->saveImage ( $_FILES ["image"], $ext );
        if($db->getImage($fid)) $fid+=1;
        if ($fid == - 1) {
          $error_msg = "Unable to store image in DB";
        } else {
          if (! file_exists ( $config->upload_dir )) {
            if (! mkdir ( $config->upload_dir )) {
              $error_msg = "Attempt to make folder: \"" . $config->upload_dir . "\" failed";
            }
          }
          $filename = str_pad ( $fid, $config->pad_length, "0", STR_PAD_LEFT ) . "." . $ext;
          move_uploaded_file ( $_FILES ["image"] ["tmp_name"], $config->upload_dir . $filename );

          chmod('assets/img/'.$filename, 0755);
        }
      }
    }
  } else if ($_FILES ["image"] ["error"] == UPLOAD_ERR_INI_SIZE || $_FILES ["image"] ["error"] == UPLOAD_ERR_FORM_SIZE) {
    $error_msg = "File is too large.";
  } else {
    $error_msg = "An error occured. Please try again. <!-- " . $_FILES ["image"] ["error"] . " -->";
  }
}

if(!empty($_POST['name']) or !empty($_POST['price']) or !empty($_POST['description'])){
  require_once 'data/list.php';
  $ing = new Ingredient();
  if(!empty($_POST['name'])){
    $ing->name = strip_tags($_POST['name']);
  }
  else{
    $ing->name = $ingredient->name;
  }
  if($idFound==true){
    $ing->id = $id;
  }
  else{
    $id=$db->getNumberOfIngredients()+1;
    $ing->id=$id;
  }
  if(!empty($_POST['price'])){
    $ing->price= doubleval($_POST['price']);
  }
  else{
    $ing->price = $ingredient->price;
  }
  if(!empty($_POST['description'])){
    $ing->description= strip_tags($_POST['description']);
  }
  else{
    $ing->description = $ingredient->description;
  }
  if(!empty($filename)){
    $ing->imgURL=$filename;
  }
  else{
    $ing->imgURL=$ingredient->imgURL;
  }
  if(isset($_GET['action'])){
  $action = strip_tags($_GET['action']);
    if($action=="update"){
      $result =$db->updateIngredient($ing);
      if(!$result) die ("unable to update $ing");
      updateIngredientFile($ing);
    }
    else if($action=="new"){
      $result = $db->insertIngredient($ing);
      if(!$result) die( "unable to insert $ing");
      if(!addIngredientToTable($ing)) die("Unable to write ingredient to file");
    }
  }
  else{
    die("action not recognized");
  }
}

function parseFileSuffix($iType) {
  if ($iType == 'image/jpeg') {
    return 'jpg';
  }
  if ($iType == 'image/gif') {
    return 'gif';
  }
  if ($iType == 'image/png') {
    return 'png';
  }
  if ($iType == 'image/tif') {
    return 'tif';
  }
  return '';
}




include 'templates/header.php';
include 'templates/jumbotron.php';
?>
<main>

        <div class="container">
<?php
if($idFound==true){
  echo "Editing ".$ingredient->name;
}
?>
			<div class="row main">
				<div class="main-login main-center">
					<form class="form-inline" enctype="multipart/form-data" method="post" action="#">
						<div class="form-group">
							<label for="name" class="cols-sm-2 control-label">Ingredient Name</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                  <input type="text" class="form-control" name="name" id="name"  placeholder="<?php if(isset($ingredient))echo $ingredient->name;?>"/>
								</div>
							</div>
						</div><br>

						<div class="form-group">
							<label for="price" class="cols-sm-2 control-label">Price</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="price" id="price"  placeholder="<?php if(isset($ingredient))echo $ingredient->price;?>"/>
								</div>
							</div>
						</div><br>

						<div class="form-group">
							<label for="description" class="cols-sm-2 control-label">Description</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="description" id="description"  placeholder="<?php if(isset($ingredient))echo $ingredient->description;?>"/>
								</div>
							</div>
						</div><br>
 <div class="form-group">
          <label class="sr-only" for="image">Upload Image</label>
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
          <input type="file" class="form-control" name="image" id="image" />
        </div>
        <button type="submit" class="btn btn-default">
          <span class="glyphicon glyphicon-upload" aria-label="Upload"></span>
        </button><br>


					</form>
				</div>
			</div>
		</div>
</main>


<?php include 'templates/footer.php';?>'
