<?php
require_once ("ingredient.php");
require_once ("comment.php");
class Database extends PDO {
	public function __construct() {
		parent::__construct ( "sqlite:" . __DIR__ . "/../ify2.db" );
	}
	function getNumberOfComments() {
		$comment_num = $this->query ( "SELECT count(*)  FROM comment" );
		return $comment_num->fetchColumn ();
	}
  function getNumberOfCommentsForIngredient($ing){
    $comment_num = $this->query("SELECT count(*) FROM comment WHERE ingredient_name LIKE '%$ing->name%'");
    return $comment_num->fetchColumn();
  }
  function getNumberOfIngredients() {
		$ingredient_num = $this->query ( "SELECT count(*)  FROM ingredient" );
		return $ingredient_num->fetchColumn ();
	}
  function getRatingStars($numStars){
    $stars = "";
    for($i=0;$i<5;$i++){
      if($i<$numStars){
        $stars .= "<span class=\"glyphicon glyphicon-star\"></span>\n";
      }
      else {
        $stars .= "<span class=\"glyphicon glyphicon-star-empty\"></span>\n";
      }
    }
    return $stars;
  }

  function getIngredientbyID($id){
    $sql = "SELECT * FROM ingredient WHERE id LIKE '%$id%'";
    $result = $this->query($sql);
    if($result===false){
      print_r($this->errorInfo());
      return array();
    }
    return Ingredient::getIngredientFromRow($result->fetch());
  }
	function getComments() {
		$sql = "SELECT * FROM comment";
		$result = $this->query ( $sql );
		if ($result === FALSE) {
			// Only doing this for class. Would never do this in real life
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return array ();
		}
		$comments = array ();
		foreach ( $result as $row ) {
			$comments [] = Comment::getCommentFromRow ( $row );
		}
		return $comments;
	}
  function getIngredients() {
		$sql = "SELECT * FROM ingredient";
		$result = $this->query ( $sql );
		if ($result === FALSE) {
			// Only doing this for class. Would never do this in real life
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return array ();
		}
		$ingredients = array ();
		foreach ( $result as $row ) {
			$ingredients [] = Ingredient::getIngredientFromRow ( $row );
		}
		return $ingredients;
	}
  function getCommentsForIngredient($ing){
    $sql = "SELECT * FROM comment WHERE ingredient_name LIKE '%$ing->name%'";
    $result = $this->query($sql);
    if($result === false){
      print_r($this->errorInfo());
      return array();
    }
    $comments = array();
    foreach($result as $row){
      $comments[] = Comment::getCommentFromRow($row);
    }
    return $comments;

  }
  function getRatingsFromComments($ingredient){
    $sql = "SELECT rating FROM comment WHERE ingredient_name LIKE '%$ingredient->name%'";
    $result = $this->query($sql);
    if($result === false){
      print_r($this->errorInfo());
      return array();
    }
    $ratings = array();
    foreach ($result as $row){
      $ratings[]= Comment::getRatingFromRow($row);
    }
    return $ratings;
  }
  function averageRating($ingredient){
    $ratings = $this->getRatingsFromComments($ingredient);
    $sum=0;
    $count=0;
    foreach($ratings as $r){
      $sum+=$r;
      $count++;
    }
    if($count==0) return $sum;
    return $sum / $count;
  }
	/**
	 * Functions needed for the search example *
	 */
	function getNumberOfResults($query_term) {
		$query_term = SQLite3::escapeString ( $query_term );
		$sql = "SELECT COUNT (*) FROM ingredient
				WHERE i_name LIKE '%$query_term%' ";
		// echo "<p>$sql</p>";
		$result = $this->query ( $sql );
		return $result->fetchColumn ();
	}
	function searchForResults($query_term) {
		$query_term = SQLite3::escapeString ( $query_term );
		$sql = "SELECT i_name, id, price, description, imgURL FROM ingredient
          WHERE (i_name LIKE '%$query_term%')";
		$result = $this->query ( $sql );
		if ($result === FALSE) {
			echo $sql;
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return array ();
		}
		$ingredients = array ();
		foreach ( $result as $row ) {
			$ingredients [] = Ingredient::getIngredientFromRow ( $row );
		}
		return $ingredients;
	}
	/*
	 * Functions used in the update data example
	 */
	function getCommentDetails($id) {
		$sql = "SELECT * FROM comment WHERE id = $id";
		$result = $this->query ( $sql );
		if ($result === FALSE) {
			// Only doing this for class. Would never do this in real life
			echo $sql;
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return NULL;
		}
		return Comment::getCommentFromRow ( $result->fetch () );
	}
	function updateIngredient($ingredient) {
		$sql = "UPDATE ingredient SET i_name= :ingredient_name, price=:ingredient_price, description = :ingredient_description, imgURL = :ingredient_imgURL WHERE id = $ingredient->id";
		$stm = $this->prepare ( $sql );
		return $stm->execute ( array (
      ":ingredient_name" => $ingredient->name,
        ":ingredient_price" => $ingredient->price,
        ":ingredient_description" => $ingredient->description,
        ":ingredient_imgURL" => $ingredient->imgURL
		) );
	}
	function updateComment($comment) {
		$sql = "UPDATE comment SET c_name = :comment_name, rating = :comment_rating, words = :comment_words, ingredient_name = :ingredient WHERE id = $comment->id";
		$stm = $this->prepare ( $sql );
		return $stm->execute ( array (
        ":comment_name" => $comment->name,
				":comment_rating" => $comment->rating,
				":comment_words" => $comment->words,
				":ingredient" => $comment->ingredient
		) );
	}

	/*
	 * Function used to support deletion of an album
	 */
	function deleteComment($comment) {
		$sql = "DELETE FROM comment WHERE id LIKE %$comment->id%";
		if ($this->exec ( $sql ) === FALSE) {
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return FALSE;
		}
		return TRUE;
	}
	function deleteIngredient($ingredient) {
    $sql = "DELETE FROM ingredient WHERE id LIKE %$ingredient->id%";
		if ($this->exec ( $sql ) === FALSE) {
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return FALSE;
		}
		return TRUE;
	}
  function insertComment($comment){

    $sql = "INSERT INTO comment (c_name, rating, words, id, ingredient_name)
            VALUES (:c_name, :rating, :words, :id, :ingredient_name)";
    $stm = $this->prepare($sql);
    return $stm->execute(array(
      ":c_name" => $comment->name,
      ":rating" => $comment->rating,
      ":words"  => $comment->words,
      ":id"     => $comment->id,
      ":ingredient_name" => $comment->ingredient
    ));
  }
  function insertIngredient($ingredient){
    $sql = "INSERT INTO ingredient (i_name, price, description, imgURL, id)
            VALUES (:i_name, :price, :description, :imgURL, :id)";
    $stm = $this->prepare($sql);
    return $stm->execute(array(
      ":i_name" => $ingredient->name,
      ":price" => $ingredient->price,
      ":description" => $ingredient->description,
      ":imgURL" => $ingredient->imgURL,
      ":id" => $ingredient->id
    ));
  }
  public function getNumberOfImages(){
    $img_num = $this->query("SELECT count(*)  FROM images");
    return $img_num->fetchColumn();
  }

  public function saveImage($imgArray, $ext){
    $sql = "INSERT INTO images (name, type, size, ext) VALUES (?,?,?,?)";
    $stm = $this->prepare($sql);
    $values = array(
      $imgArray["name"],
      $imgArray["type"],
      $imgArray["size"],
      $ext
    );
    if($stm->execute($values) === FALSE){
      return -1;
    }else{
      return $this->lastInsertId("id");
    }
  }
  public function deleteImage($id){
    $sql = "DELETE FROM images WHERE id LIKE %$id%";
 		if ($this->exec ( $sql ) === FALSE) {
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return FALSE;
		}
		return TRUE;
  }

  public function getImages(){
    $sql = "SELECT * FROM images";
    return $this->query($sql);
  }

  public function getImage($id){
    $sql = "SELECT * FROM images WHERE id ='$id'";
    return $this->query($sql)->fetch();
  }


}
