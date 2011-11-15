<?php

session_start();
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);

//Sets the $userid variable.
require_once('ajax/user.php');
require_once('models/db_lib.php');
require_once('models/character.php');

$charJSON = filter_input(INPUT_POST, 'char');
$char = json_decode($charJSON);

if ( !$char ){
	echo json_encode(array('success'=>false, 'info'=>'Invalid JSON passed :: '+$charJSON));
	die();
}

if ( isset($char->id) ){
	$existingChar = Character::factoryChar($char->id, $userid);
	if ( $existingChar->getUserID() != $userid ){
		echo json_encode(array('success'=>false, 'info'=>'Character does not exist'));
		die();
	}
	
	$existingCards = $existingChar->getAllCards();
	
	// Find the cards in the new character verstion and the old version
	// We will build arrays listing their IDs
	// This is becuase we get different objects from the character and the 
	// passed POST parameter
	$newCardSet = array();
	foreach ( $char->deck as $card ){
		$newCardSet[] = $card->id;
	}
	
	$oldCardSet = array();
	foreach( $existingCards as $card ){
		$oldCardSet[] = $card->id;
	}
	
	// Find the cards to add and subtract based on the differences
	$cardsToAdd 	= array_diff($newCardSet, $oldCardSet);
	$cardsToRemove 	= array_diff($oldCardSet, $newCardSet);
	
	// Remove the cards nessisary
	foreach( $existingCards as $card ){
		if ( in_array($card->id, $cardsToRemove) ){
			$existingChar->removeCard($card);
		}
	}
	
	// Add the cards nessisary
	foreach( $char->deck as $cardId ){
		if ( in_array($cardId, $cardsToAdd) ){
			$card = new Card($cardId);
			$existingChar->addCard($card);
		}
	}
	
	// Update the character information
	
	$existingChar->setCharName($char->name);
	$existingChar->setCharDesc($char->description);
	$existingChar->setTotalUP($char->totalUP);
	$existingChar->setCurrentUP($char->swapBuffer);
	
	$existingChar->saveCharacter();
	echo json_encode(array('success'=>true, 'info'=>'Character saved!'));
	die();
}else{
	$character = new Character($userid, $char->name, $char->totalUP);
	$character->setCharDesc($char->description);
	$character->setCurrentUP($char->swapBuffer);
	if ( $char->deck ){
		foreach( $char->deck as $cardId ){
			$card = new Card($cardId);
			$character->addCard($card);
		}
	}
	
	$character->saveCharacter();
	
	echo json_encode(array('success'=>true, 'info'=>'Character saved!'));
	die();
}



print_r($char);
die();


?>
//$char->saveCharacter();
//$_SESSION['char'] = serialize($char);
