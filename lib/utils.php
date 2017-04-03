<?php


class Utils{
	/**
	 * File containing utility functions
	 */
	//Adding a function to check if input has error
	//and if so return class
	public static function checkForError($field_name){
		//Need to tell php we are using global errors
		global $errors;
		//Short hand if statement
		return (isset($errors[$field_name]))? " error": "";
	}
	//Use this because form_data doesn't always have the value set
	public static function getValue($field_name){
		global $form_data;
		return (isset($form_data[$field_name]))? $form_data[$field_name] : "";
	}

	public static function removeParameterFromUrl($parm){
		$current_url = $_SERVER['REQUEST_URI'];
		$reg1 = "/&". $parm ."=[^&#]+/";
		$reg2 = "/\?". $parm ."=[^&#]+/";
		//Remove the current parameter
		$current_url = preg_replace($reg1, '', $current_url);
		//Also need to rempove if it is the first parameter
		$current_url = preg_replace($reg2, '?', $current_url);
		return $current_url;
	}

	public static function makeSureURLIsQueryString($url){
		if(strpos($url,"?") === FALSE){
			return $url . "?"; //BE WARNED THIS BREAKS IF URL CONTAINS #
		}
		return $url;
	}
	public static function createPagination($current_page, $max_pages){
		$current_url = Utils::makeSureURLIsQueryString(
								Utils::removeParameterFromUrl("p")
						);
		//Start widget
		//Example of using parameters inside a string and espcaping double-quotes
		echo "<ul class=\"pagination\">\n";
		if($current_page == 1){
			echo "<li class=\"disabled\"><span><span aria-hidden=\"true\">&laquo;</span></span></li>\n";

		}else{
			echo "	<li>\n";
			echo "		<a aria-label=\"Previous\" href=\"$current_url&p=" . strval($current_page-1) . "\">";
			echo "			<span aria-hidden=\"true\">&laquo;</span>";
			echo "		</a>\n";
			echo "	</li>\n";
		}
		for($i=1; $i <= $max_pages; $i++){
			if($current_page == $i){
				echo "	<li class=\"active\">\n";
			}else{
				echo "	<li>\n";
			}
			echo "		<a href=\"$current_url&p=$i\">$i</a>\n";
			echo "	</li>\n";
		}
		if($current_page >= $max_pages){
			echo "	<li class=\"disabled\"><span><span aria-hidden=\"true\">&raquo;</span></span></li>\n";

		}else{
			echo "	<li>\n";
			echo "		<a aria-label=\"Next\" href=\"$current_url&p=" . strval($current_page+1) . "\">\n";
			echo "			<span aria-hidden=\"true\">&raquo;</span>\n";
			echo "		</a>";
			echo "	</li>\n";
		}
		echo '</ul>';
	}
}
