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
function writeComments($comment){
  $fh = fopen('comments.csv', 'w+') or die("Can't open file");
  fputcsv ( $fh, array_keys ( get_object_vars ( $users [0] ) ) );
  for($i = 0; $i < count ( $users ); $i ++) {
    fputcsv ( $fh, get_object_vars ( $users [$i] ) );
  }
  fclose ( $fh );
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
