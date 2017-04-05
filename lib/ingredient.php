<?php
  class Ingredient{
    public $name;
    public $id;
    public $price;
    public $description;
    public $imgURL;

    function __construct($name="",$id=0,$description="", $price=0, $imgURL =""){
      $this->name = $name;
      $this->id = $id;
      $this->description=$description;
      $this->price=$price;
      $this->imgURL=$imgURL;
    }
    public static function getIngredientFromRow($row){
      $ingredient = new Ingredient();
      $ingredient->name = $row['i_name'];
      $ingredient->id = $row['id'];
      $ingredient->price = $row['price'];
      $ingredient->description = $row['description'];
      $ingredient->imgURL = $row['imgURL'];
      return $ingredient;
    }
    function __toString(){
      return $this->name;
    }
  }
