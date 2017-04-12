<?php
require_once 'templates/page_setup.php';
$comment;
$com;
$id;
$idFound=false;
$db=new Database();
if(isset($_GET['action']) and isset($_SESSION['status']) and $_SESSION['status'] =="Admin"){
  if(isset($_GET['id'])){

    $idFound=true;
    $id = intval($_GET['id']);
    $comment = $db->getCommentDetails($id);
    if(strip_tags($_GET['action'])=="delete"){
      require_once 'data/list.php';
      $db->deleteComment($comment);
      deleteCommentFromFile($comment);
      header('location: products.php');
    }
  }
}
else{
  header("location: index.php");
}
if(!empty($_POST['name']) or !empty($_POST['rating']) or !empty($_POST['words']) or !empty($_POST['ingredient'])){
  require_once 'data/list.php';
  $com = new Ingredient();
  if(!empty($_POST['name'])){
    $com->name = strip_tags($_POST['name']);
  }
  else{
    $com->name = $comment->name;
  }
  if($idFound==true){
    $com->id = $id;
  }
  else{
    $id=$db->lastInsertID()+1;
    $com->id=$id;
  }
  if(!empty($_POST['rating'])){
    $com->rating= intval($_POST['rating']);
  }
  else{
    $com->rating = $comment->rating;
  }
  if(!empty($_POST['words'])){
    $com->words= strip_tags($_POST['words']);
  }
  else{
    $com->words = $comment->words;
  }
  if(!empty($_POST['ingredient'])){
    $com->ingredient=strip_tags($_POST['ingredient']);
  }
  else{
    $com->ingredient=$comment->ingredient;
  }

  if(isset($_GET['action'])){
  $action = strip_tags($_GET['action']);
    if($action=="edit"){
      $result =$db->updateComment($com);
      if(!$result) die ("unable to update $com");
      updateCommentFile($com);
    }
  }
  else{
    die("action not recognized");
  }
}
include 'templates/header.php';
include 'templates/jumbotron.php';
?>
<main>

        <div class="container">
<?php
if($idFound==true){
  echo "Editing ".$comment->name."'s comment";
}
?>
			<div class="row main">
				<div class="main-login main-center">
					<form class="form-inline" enctype="multipart/form-data" method="post" action="#">
						<div class="form-group">
							<label for="name" class="cols-sm-2 control-label">Commenter Name</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                  <input type="text" class="form-control" name="name" id="name"  placeholder="<?php if(isset($comment))echo $comment->name;?>"/>
								</div>
							</div>
						</div><br>

						<div class="form-group">
							<label for="rating" class="cols-sm-2 control-label">Rating</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="rating" id="rating"  placeholder="<?php if(isset($comment))echo $comment->rating;?>"/>
								</div>
							</div>
						</div><br>

						<div class="form-group">
							<label for="words" class="cols-sm-2 control-label">Words</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="words" id="words"  placeholder="<?php if(isset($comment))echo $comment->words;?>"/>
								</div>
							</div>
						</div><br>
						<div class="form-group">
							<label for="ingredient" class="cols-sm-2 control-label">Ingredient</label>
							<div class="cols-sm-8">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="ingredient" id="ingredient"  placeholder="<?php if(isset($comment))echo $comment->ingredient;?>"/>
								</div>
							</div>
						</div><br>
            <input type="submit" value ="Submit">


					</form>
				</div>
			</div>
		</div>
</main>


<?php include 'templates/footer.php';?>'
