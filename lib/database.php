<?php
require_once ("ingredient.php");
require_once ("comment.php");
class Database extends PDO {
	public function __construct() {
		parent::__construct ( "sqlite:" . __DIR__ . "./../ify2.db" );
	}
	function getNumberOfComments() {
		$comment_num = $this->query ( "SELECT count(*)  FROM comment" );
		return $comment_num->fetchColumn ();
	}
  function getNumberOfIngredients() {
		$ingredient_num = $this->query ( "SELECT count(*)  FROM ingredient" );
		return $ingredient_num->fetchColumn ();
	}
	/**
	 * Functions used by the select page to sort full music database
	 */
  function getIngredientsByField($field, $num_returned = 25, $offset = 0) {
		$sql = "SELECT i_name, price, description, imgURL FROM ingredient
	           ORDER BY $field ASC LIMIT $num_returned OFFSET $offset";
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
	/**
	 * Functions needed for the search example *
	 */
	function getNumberOfResults($query_term) {
		$query_term = SQLite3::escapeString ( $query_term );
		$sql = "SELECT (SELECT * FROM comment) AS comments (SELECT * FROM ingredient) AS ingredients FROM DUAL
				WHERE (ingredient_name LIKE '%$query_term%' ";
		// echo "<p>$sql</p>";
		$result = $this->query ( $sql );
		return $result->fetchColumn ();
	}
	function searchForResultsAndSort($query_term, $sort_col = "i_name", $num_returned = 25, $offset = 0) {
		$query_term = SQLite3::escapeString ( $query_term );
		switch ($sort_col) {
			case "i_name" :
				$sort_col = "i_name";
				$sec_sort = "price";
        break;
      case "price" :
        $sort_col = "price";
        $sec_sort = "i_name";
        break;
		}
		$sql = "SELECT (SELECT * FROM comment) AS comments (SELECT i_name, price, description, imgURL FROM ingredient) AS ingredients FROM DUAL
          WHERE (i_name LIKE '%$query_term%')
					ORDER BY $sort_col ASC, $sec_sort ASC
					LIMIT $num_returned OFFSET $offset";
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
		$sql = "SELECT id FROM comment WHERE id = $id";
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
		$sql = "UPDATE ingredient SET i_name= :ingredient_name, price=:ingredient_price, description = :ingredient_description, imgURL = :ingredient_imgURL WHERE id = $ingredient";
		$stm = $this->prepare ( $sql );
		return $stm->execute ( array (
      ":ingredient_name" => $ingredient->name,
        ":ingredient_price" => $ingredient->price,
        ":ingredient_description" => $ingredient->description,
        ":ingredient_imgURL" => $ingredient->imgURL
		) );
	}
	function updateComment($comment) {
		$sql = "UPDATE comment SET c_name = :comment_name, rating = :comment_rating, words = :comment_words, ingredient_name = :ingredient WHERE id = $comment";
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
		$sql = "DELETE FROM comment WHERE id = $comment";
		if ($this->exec ( $sql ) === FALSE) {
			echo '<pre class="bg-danger">';
			print_r ( $this->errorInfo () );
			echo '</pre>';
			return FALSE;
		}
		return TRUE;
	}
}
