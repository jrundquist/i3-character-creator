<?php
session_start();
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);

//Sets the $userid variable.
require_once('ajax/user.php');
require_once('models/db_lib.php');
require_once('models/card.php');
require_once('models/deck.php');

/** aasort
 * A function used to sort an array based on 
 * the value of one of it's keys
 * 
 * Used in this code for sorting the deck based on 
 * the name of the cards before sending it to the 
 * client
 */

$expires = 60*60*6; // 6 hour cache
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');


function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

	$deck = new Deck($userid);
	if ( !$deck ){
		echo json_encode(array());
		die();
	}
	
	$deckarr = $deck->getDeckArr();
	$return = array();
	foreach($deckarr as $card) {
		$return[] = array(
			"card"		=> $card,
			"name" 		=> $card->getStat('name'), // only seperated out sp we can sort by this
			"listname" 	=> createCardListName($card)
		);
	}
	aasort($return, "name");
	echo str_replace(array('atk', 'bst', 'def'), array('attack', 'boost', 'defense'), json_encode($return));

?>