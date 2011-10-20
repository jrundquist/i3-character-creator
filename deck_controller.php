<?php
session_start();
//Sets the $userid variable.
require_once('get_userid.php');

require_once('cb_backend/deck.php');
require_once('cb_backend/card.php');

/** aasort
 * A function used to sort an array based on 
 * the value of one of it's keys
 * 
 * Used in this code for sorting the deck based on 
 * the name of the cards before sending it to the 
 * client
 */
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

if(isset($_GET['type']) || isset($_POST['type'])) {
	$command = isset($_GET['type'])?$_GET['type']:$_POST['type'];
	$deck = new Deck($userid);
	if ( !$deck ){
		echo json_encode("failure");
		exit;
	}
	
	if ($command == 'get_deckarray'){

		$cardtype = isset($_GET['cardtype'])?$_GET['cardtype']:'all';
		$deck->sortDeck();
		$deckarr = $deck->getDeckArr();
		$return = array();
		foreach($deckarr as $tempindex => $tempcard) {
			if ($cardtype == 'all' || $tempcard->getTypeAsStr() == $cardtype){
				$return[] = array(
					"id"		=> $tempcard->getStat("id"),
					"frontimg" 	=> $tempcard->getStat("picture"),
					"backimg"  	=> $tempcard->getStat("backpic"),
					"listname" 	=> createCardListName($tempcard),
					"name"	   	=> $tempcard->getStat("name"),
					"cardtype" 	=> $tempcard->getTypeAsStr()
				);
			}
		}
		aasort($return, "name");
		echo json_encode($return);
	}
}
?>