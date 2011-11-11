<?php
session_start();


set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);


//Sets the $userid variable.
require_once('user.php');


require_once('../models/db_lib.php');

$characters = get_cbdb_users_characters($userid, get_cbdb_connection());
$result = array();
foreach($characters as $charcater) {
	// Add image to the character information
	$raceId = get_race_card_id($charcater['charid'], get_cbdb_connection());
	if ( $raceId ){
		$raceCard = new Card($raceId);
		$charcater['image'] = $raceCard->getStat("backpic");
	}else{
		$charcater['image'] = false;
	}
	
	// If the character's name is null, then set it to a default value
	if ( strlen(trim($charcater['charname'])) == 0){
		$charcater['charname'] = '[Nameless Character]';
	}
	
	// Append this character to to the results
	$result[] = $charcater;
}

// Echo the results as a JSON string
echo json_encode($result);

?>