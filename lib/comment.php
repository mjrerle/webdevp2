<?php
  class Comment{
    public $name;
    public $rating;
    public $words;
    public $id;
    public $ingredient;
    /*
    function __construct($name="",$rating,$words="", $id, $ingredient=""){
      $this->name = $name;
      $this->rating = $rating;
      $this->words=$words;
      $this->id=$id;
      $this->ingredient=$ingredient;
    }
    */
    public static function getCommentFromRow($row){
      $comment = new Comment();
      $comment->name = $row['c_name'];
      $comment->rating = $row['rating'];
      $comment->words = $row['words'];
      $comment->ingredient = $row['ingredient_name'];
      $comment->id = $row['id'];
      return $comment;
    }
    public static function getRatingFromRow($row){
      return $row['rating'];
    }
    function __toString(){
      return $this->name . '(' . $this->rating . ')';
    }
  }
