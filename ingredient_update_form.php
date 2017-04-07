<?php
require_once 'templates/page_setup.php';
$ingredient;
$id;
$db = new Database();
$max_file_size=1000000;
if(isset($_GET['id']) and $_SESSION['status']=='Admin'){
  $id = intval($_GET['id']);
  $ingredient = $db->getIngredientByID($id);
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
        }
      }
    }
  } else if ($_FILES ["image"] ["error"] == UPLOAD_ERR_INI_SIZE || $_FILES ["image"] ["error"] == UPLOAD_ERR_FORM_SIZE) {
    $error_msg = "File is too large.";
  } else {
    $error_msg = "An error occured. Please try again. <!-- " . $_FILES ["image"] ["error"] . " -->";
  }
}
if(isset($_POST['form'])){
  require_once 'data/list.php';
  $ing = new Ingredient();
  $ing->name = strip_tags($_POST['name']);
  $ing->id = $id;
  $ing->price= strip_tags($_POST['price']);
  $ing->description= strip_tags($_POST['description']);
  if(isset($filename)){
    $ing->imgURL=$filename;
  }
  else{
    $ing->imgURL=$ingredient->imgURL;
  }

  $result =$db->updateIngredient($ing);
  if(!$result) $ing=$db->getIngredientByID($id);
  updateIngredientFile($ing);
}
$filename;

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
    <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4">
        <form action= "#" class="form-inline" method ="post" enctype="multipart/form-data">
          <input type="hidden" name ="form">
          <div class="form-group">
            <label class="sr-only" for="image">Upload Image</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
            <input type="file" class="form-control" name="image" id="image" />
          </div>
          <button type="submit" class="btn btn-default">
            <span class="glyphicon glyphicon-upload" aria-label="Upload"></span>
          </button>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="name">Name</label>
            <div class="col-sm-9">
              <input type = "text" name="name" value = "<?php echo $ingredient->name;?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label" for="price">Price</label>
            <div class="col-sm-9">
              <input type = "text" name="price" value = "<?php echo $ingredient->price;?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="description">Description</label>
            <div class="col-sm-9">
              <input type = "text" name="description" value = "<?php echo $ingredient->description;?>">
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</main>
