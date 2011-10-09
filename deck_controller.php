<?php
session_start();
//Sets the $userid variable.
require_once('get_userid.php');

require_once('cb_backend/deck.php');
require_once('cb_backend/card.php');
if(isset($_GET['type']) || isset($_POST['type'])) {
	$command = isset($_GET['type'])?$_GET['type']:$_POST['type'];
	$deck = new Deck($userid);
	switch($command) {
		case 'get_deckarray':

			if (isset($_GET['cardtype'])){
				$cardtype = $_GET['cardtype'];
			}
			else {
				$cardtype = 'all';
			}

			if(!$deck) {
				echo json_encode("failure");
			}
			else {
				$deckarr = $deck->getDeckArr();
				$toretarr = array();
				foreach($deckarr as $tempindex => $tempcard) {
					if ($tempcard->getTypeAsStr() == $cardtype || $cardtype == 'all'){
						$toretarr[$tempcard->getStat("id")] = array(
							"frontimg" => $tempcard->getStat("picture"),
							"backimg"  => $tempcard->getStat("backpic"),
							"listname" => createCardListName($tempcard),
							"cardtype" => $tempcard->getTypeAsStr()
						);
					}
				}
				echo json_encode($toretarr);
			}
			break;
		default:
			break;
	}
}
?>