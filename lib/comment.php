<?php
  class Comment{
    public $name;
    public $rating;
    public $words;
    public $id;
    public $ingredient;

    public static function getCommentFromRow($row){
      $comment = new Comment();
      $comment->name = $row['c_name'];
      $comment->rating = $row['rating'];
      $comment->words = $row['words'];
      $comment->ingredient = $row['ingredient_name'];
      return $comment;
    }
    function __toString(){
      return $this->name . '(' . $this->rating . ')';
    }
  }