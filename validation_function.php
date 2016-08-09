<?php
$errors = array();

function field_as_text($fieldname){
  $fieldname = str_replace( " ", " ", $fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}

function has_presence($value){
  return isset($value) && $value !== "";
}

function validate_presences($required_fields){
  global $errors;
  foreach ($required_fields as $field) {
    $value = trim($_POST[$field]);
    if (!has_presence($value)) {
      $errors[$field] = field_as_text($field) . " can't be blank. ";
    }
  }
}

function image_presences($required_image){
  global $errors;
  foreach ($required_image as $field) {
    $type = "type";
    $image_value = trim($_FILES[$field][$type]);
    if (!has_presence($image_value)) {
      $errors[$field] = field_as_text($field) . " can't be blank. ";
    }
  }
}

function validate_max_lengths($fields_with_max_lengths){
  global $errors;
  //expects an assoc array
  foreach ($fields_with_max_lengths as $field => $max) {
    $value = trim($_POST[$field]);
    if (!has_max_length($value,$max)) {
      $errors[$field] = field_as_text($field) . " is too long";
    }
  }
}

function has_max_length($value,$max){
  return strlen($value) <= $max;
}

function has_inclusion($value,$set){
  return in_array($value,$set);
}

function check_image_size($image,$size){
  $max_size=5000000;
  if ($size<=$max_size && $image) {
		$image_size=getimagesize($image);
		$extension=$image_size['mime'];
		$extension = strtolower(substr($extension,strrpos($extension,'/') + 1 ));
	}else {
	  return false;
	}
  return $extension;
}

function check_image_type($extension){
  if( $extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
    return false;
  }
}

?>
