<?php
session_start();
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);

//Sets the $userid variable.
require_once('ajax/user.php');
require_once('models/db_lib.php');
require_once('models/character.php');

$charId = filter_input(INPUT_POST, 'id');

$existingChar = Character::factoryChar($charId, $userid);
if ( $existingChar->getUserID() == $userid ){
	$existingChar->delCharacter();
	echo json_encode(array('success'=>true, 'info'=>'Delete'));
}else{
	echo json_encode(array('success'=>false, 'info'=>'Character Owner'));
}

?>