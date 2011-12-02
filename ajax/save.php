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
	if ( is_array($char->deck) && count($char->deck)>1){
		foreach ( $char->deck as $card ){
			$newCardSet[] = $card->id;
		}
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
	foreach( $cardsToAdd as $cardId ){
		if ( is_object($cardId) )
			$cardId = $cardId->id;
		$card = new Card($cardId);
		$existingChar->addCard($card);
	}
	
	// Update the character information
	
	$existingChar->setCharName($char->name);
	$existingChar->setCharDesc($char->description);
	$existingChar->setNotes($char->notes);
	$existingChar->setTotalUP($char->totalUP);
	$existingChar->setCurrentUP($char->swapBuffer);
	
	$saveResult = $existingChar->saveCharacter();
	
	if( $existingChar->saveCharacter() ){
		echo json_encode(array('success'=>true, 'info'=>$char->id));
	}else{
		echo json_encode(array('success'=>false, 'info'=>'Character failed to save'));
	}
	die();
}else{
	$character = new Character($userid, $char->name, $char->totalUP);
	$character->setCharDesc($char->description);
	$character->setNotes($char->notes);
	$character->setCurrentUP($char->swapBuffer);
	if ( $char->deck ){
		foreach( $char->deck as $cardId ){
			if ( is_object($cardId) )
				$cardId = $cardId->id;
			$card = new Card($cardId);
			$character->addCard($card);
		}
	}
	
	$saved = $character->saveCharacter();
	if( $saved ){
		echo json_encode(array('success'=>true, 'info'=>$saved->getCharID()));
	}else{
		echo json_encode(array('success'=>false, 'info'=>'Character failed to save'));
	}
	die();
}
?>