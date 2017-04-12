<?php
require_once 'lib/database.php';
function setupProductConnection(){
  try{
    $dbh = new Database();

    return $dbh;
  }
  catch(PDOException $e){
    echo '<pre class = "bg-danger">';
    echo 'Connection failed: ' . $e->getMessage();
    echo '</pre>';
    return FALSE;
  }
}



function dropTableByName($tname){
  global $dbh;
  $sql = "DROP TABLE IF EXISTS $tname";
  $status = $dbh->exec($sql);
  if($status === FALSE){
    echo '<pre class ="bg-danger">';
    print_r($dbh->errorInfo());
    echo '</pre>';
  }
}

function createTableIngredient(){
  $sql = "CREATE TABLE ingredient (
            id INTEGER PRIMARY KEY ASC,
            i_name varchar(50),
            price decimal(10,2),
            description varchar(255),
            imgURL varchar(255))";
  createTableGeneric($sql);
}

function createTableComment(){
  $sql = "CREATE TABLE comment (
            id INTEGER PRIMARY KEY ASC,
            c_name varchar(50),
            rating int(2),
            words varchar(500),
            ingredient_name varchar(255))";
  createTableGeneric($sql);
}
function createTableImage(){
  $sql = "CREATE TABLE images(
          id INTEGER PRIMARY KEY ASC,
          name varchar(255),
          type varchar(255),
          size int(10),
          ext varchar(5)
          )";
  createTableGeneric($sql);
}

function createTableGeneric($sql){
  global $dbh;
  $status = $dbh->exec($sql);
  if($status === false){
    echo '<pre class="bg-danger">';
    print_r ( $dbh->errorInfo () );
    echo '</pre>';
  }
}

function loadProductsIntoEmptyDatabase(){
  global $dbh;
  require_once "data/list.php";
  $ingredients = getIngredientsFromFile();
  $comments = getCommentsFromFile();
  $sql_ingredient = "INSERT INTO ingredient(i_name, price, description, imgURL, id) VALUES (:name, :price, :description, :imgURL, :id)";
  $sql_comment = "INSERT INTO comment(c_name, rating, words, ingredient_name, id) VALUES(:name, :rating, :words, :ingredient_name, :id)";
  $ing_stm = $dbh->prepare($sql_ingredient);
  $com_stm = $dbh->prepare($sql_comment);
  foreach($ingredients as $current_ingredient){
    testedInsertIngredient($current_ingredient,$ing_stm);
  }
  foreach($comments as $current_comment){
    testedInsertComment($current_comment, $com_stm);
  }
}

function testedInsertIngredient($ingredient, $stmt){
  global $dbh;
  if(!$stmt->execute(array(
    ':name'=>$ingredient['Name'],
    ':price'=>$ingredient['Price'],
    ':description'=>$ingredient['Description'],
    ':imgURL'=>$ingredient['IMGURL'],
    ':id'=>$ingredient['ID']
    ))){
    echo '<pre class="bg-danger">';
    print_r($dbh->errorInfo());
    echo '</pre>';
  }
}

function testedInsertComment($comment, $stmt){
  global $dbh;
  if(!$stmt->execute(array(
    ':name'=>$comment['Commenter Name'],
    ':rating'=>$comment['Rating'],
    ':words'=>$comment['Words'],
    ':ingredient_name'=>$comment['Ingredient Name'],
    ':id'=>$comment['ID']
  ))){
    echo '<pre class="bg-danger">';
    print_r($dbh->errorInfo());
    echo '</pre>';
  }
}
