<?php
function getIngredientsFromFile(){
  //Pretty much cut and paste from our original file
  $ingredients = array();
  /* This line is because of the file having MAC line endings */
  ini_set('auto_detect_line_endings',TRUE);
  $file_array = file(dirname(__FILE__) . "/products.csv", $flag=FILE_IGNORE_NEW_LINES);
  //The first line is going to be the keys
  //so we are going to "split" the line into an array using ","
  $file_keys = str_getcsv($file_array[0]);

  //Since we read in line[0] let's remove it from the array
  unset($file_array[0]);
  foreach($file_array as $line){
    //The best way to split a csv line because entries
    //may have a comma.
    $values = str_getcsv($line);
    //Use for loop to get key from key array
    $ing = array();

    for($i=0; $i < count($values); $i++){
        $ing[$file_keys[$i]] = $values[$i];
    }

    $ingredients[] = $ing;
  }
  return $ingredients;
}
function addIngredientToTable($ingredient){
  $ingredientExists=false;
  $ingredients = getIngredientsFromFile();
  for($i=0; $i<count($ingredients);$i++)  if($ingredients[$i]["Name"] == $ingredient->name) $ingredientExists = true;
  if(!$ingredientExists){
    array_push($ingredients,array('Name'=>$ingredient->name, 'Price'=>$ingredient->price, 'Description'=>$ingredient->description, 'IMGURL'=>$ingredient->imgURL, 'ID'=>$ingredient->id));
    writeIngredients($ingredients);
    return true;
  }
  return false;
}

function updateIngredientFile($ing){
  $ingredients = getIngredientsFromFile();
  for($i=0;$i<count($ingredients);$i++){
    if($ingredients[$i]["ID"] == $ing->id){
      $ingredients[$i]["Name"] = $ing->name;
      $ingredients[$i]["Price"] = $ing->price;
      $ingredients[$i]["Description"] = $ing->description;
      $ingredients[$i]["IMGURL"] = $ing->imgURL;
    }
  }
  writeIngredients($ingredients);
}
function updateCommentFile($com){
  $comments = getCommentsFromFile();
  for($i=0;$i<count($comments);$i++){
    if($comments[$i]["ID"] == $com->id){
      $comments[$i]["Commenter Name"] = $com->name;
      $comments[$i]["Rating"] = $com->rating;
      $comments[$i]["Words"] = $com->words;
      $comments[$i]["Ingredient Name"] = $com->ingredient;
    }
  }
  writeComments($comments);
}

function addCommentToTable($comment){
  $comments = getCommentsFromFile();
  array_push($comments,array('Commenter Name'=>$comment->name, 'Rating'=>$comment->rating, 'Words'=>$comment->words, 'Ingredient Name'=>$comment->ingredient, 'ID'=>$comment->id));
  writeComments($comments);
}


function writeComments($comments){
  $fh = fopen('data/usercomments.csv', 'w+') or die("Can't open file");
  $heading=array("Commenter Name,Rating,Words,Ingredient Name,ID");
  if(count($comments)==0) fputcsv($fh, array_keys($heading));
  else{
    fputcsv ( $fh, array_keys (( ($comments [0]) ) ) );
  }
  for($i = 0; $i < count ( $comments ); $i ++) {
    fputcsv ( $fh, array_values ( ($comments [$i])) ) ;
  }
  fclose ( $fh );
}
function writeIngredients($ingredients){
  $fh = fopen('data/products.csv', 'w+') or die("Can't open file");
  $heading=array("Name,Price,Description,IMGURL,ID");
  if(count($ingredients)==0)  fputcsv ( $fh, array_keys($heading) );
  else{
    fputcsv($fh, array_keys($ingredients[0]));
  }
  for($i = 0; $i < count ( $ingredients ); $i ++) {
    fputcsv ( $fh, array_values ( $ingredients [$i] ) );
  }
  fclose ( $fh );
}
function deleteIngredientFromFile($ingredient){
  $arr = getIngredientsFromFile();
  $out = array();
  foreach($arr as $line){
    if(($line["ID"]) != $ingredient->id){
      $out[]=$line;
    }
  }
  fclose($arr);
  writeIngredients($out);
}
function deleteCommentFromFile($comment){
  $arr = getCommentsFromFile();
  $out = array();
  foreach($arr as $line){
    if(($line["ID"]) != $comment->id){
      $out[]=$line;
    }
  }
  fclose($arr);
  writeComments($out);
}
function getCommentsFromFile(){
  //Pretty much cut and paste from our original file
  $comments = array();
  /* This line is because of the file having MAC line endings */
  ini_set('auto_detect_line_endings',TRUE);
  $file_array = file(dirname(__FILE__) . "/usercomments.csv", $flag=FILE_IGNORE_NEW_LINES);
  //The first line is going to be the keys
  //so we are going to "split" the line into an array using ","
  $file_keys = str_getcsv($file_array[0]);

  //Since we read in line[0] let's remove it from the array
  unset($file_array[0]);
  foreach($file_array as $line){
    //The best way to split a csv line because entries
    //may have a comma.
    $values = str_getcsv($line);
    //Use for loop to get key from key array
    $com = array();

    for($i=0; $i < count($values); $i++){
        $com[$file_keys[$i]] = $values[$i];
    }

    $comments[] = $com;
  }
  return $comments;
}
