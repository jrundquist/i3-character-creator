<?php
session_start();
//Sets the $userid variable.
require_once('get_userid.php');

require_once('cb_backend/character.php');
if(isset($_REQUEST['type'])) {
	$char = isset($_SESSION['char'])?unserialize($_SESSION['char']):false;
	
	
	// Switch over the sent 'type' of request
	switch($_REQUEST['type']) {
		case 'new_character':
			$char = new Character($userid, '', 0);
			$_SESSION['char'] = serialize($char);
			break;
		case 'get_character':
			if(!isset($_POST['charid'])) {
				$charid = 0;
			}
			else {
				//We want the integer value, not a string.
				$charid = intval($_POST['charid']);
				if(!$char) {
					$char = new Character($userid, '', 0);
				}
				$char->getCharacter($charid, $userid);
				$_SESSION['char'] = serialize($char);
			}
			break;
		case 'save_character':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				$char->saveCharacter();
				$_SESSION['char'] = serialize($char);
			}
			break;
		case 'del_character':
			if(!isset($_POST['charid'])) {
				echo json_encode("failure");
			}
			else{
				$charid = intval($_POST['charid']);
				$delchar = new Character($userid, '', 0);
				$delchar->getCharacter($charid, $userid);
				if($delchar->getCharID() == $char->getCharID()) {
					$_SESSION['char'] = serialize(new Character($userid, '', 0));
				}
				$delchar->delCharacter();
			}
			break;
		case 'get_charid':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getCharID());
			}
			break;
		case 'set_charname':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				if(isset($_POST['newval'])) {
					$char->setCharName($_POST['newval']);
					$char->saveCharacter();
					$_SESSION['char'] = serialize($char);
				}
			}
			break;
		case 'get_charname':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getCharName());
			}
		    break;
		case 'set_totalup':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				if(isset($_POST['newval'])) {
					$char->setTotalUP(intval($_POST['newval']));
					$char->saveCharacter();
					$_SESSION['char'] = serialize($char);
				}
			}
			break;
		case 'get_totalup':
				if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getTotalUP());
			}
			break;
		case 'get_currentup':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getCurrentUP());
			}
			break;
		case 'set_chardesc':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				if(isset($_POST['newval'])) {
					$char->setCharDesc($_POST['newval']);
					$char->saveCharacter();
					$_SESSION['char'] = serialize($char);
				}
			}
			break;
		case 'get_chardesc':
				if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getCharDesc());
			}
			break;
		case 'get_lastmod':
				if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getLastModified());
			}
			break;
		case 'get_vitality':
				if(!$char) {
				echo json_encode("failure");
			}
			else {
				echo json_encode("".$char->getVit());
			}
			break;
		case 'get_bodyall':
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				$tempbody = $char->getBody();
				echo json_encode($tempbody);
			}
			break;
		case 'get_mindall':
				if(!$char) {
				echo json_encode("failure");
			}
			else {
				$tempmind = $char->getMind();
				echo json_encode($tempmind);
			}
			break;
		case 'get_soulall':
		    if(!$char) {
				echo json_encode("failure");
			}
			else {
				$tempsoul = $char->getSoul();
				echo json_encode($tempsoul);
			}
			break;
		case 'add_card':
		    if(!$char) {
				echo json_encode("failure");
			}
			else {
				$cardid = isset($_GET['cardid'])?$_GET['cardid']:$_POST['cardid'];
				$tempcard = new Card($cardid);
				$char->addCard($tempcard);
				$char->saveCharacter();
				$_SESSION['char'] = serialize($char);
			}
			break;
		case 'remove_card':
		    if(!$char) {
				echo json_encode("failure");
			}
			else {
				$cardid = isset($_GET['cardid'])?$_GET['cardid']:$_POST['cardid'];
				$tempcard = new Card($cardid);
				$char->removeCard($tempcard);
				$char->saveCharacter();
				$_SESSION['char'] = serialize($char);
			}
			break;
		case 'get_charcards':

			if (isset($_GET['cardtype'])){
				$cardtype = $_GET['cardtype'];
			}
			else {
				$cardtype = 'all';
			}
			if(!$char) {
				echo json_encode("failure");
			}
			else {
				$newarr = $char->getAllCards();
				$toret = array();
				foreach($newarr as $index => $card) {
					if ($card->getTypeAsStr() == $cardtype || $cardtype == 'all'){
						$toret[$card->getStat("id")] = array(
							"frontimg" => $card->getStat("picture"),
							"backimg"  => $card->getStat("backpic"),
							"listname" => createCardListName($card),
							"cardtype" => $card->getTypeAsStr()
						);
					}
				}
				echo json_encode($toret);
			}
			break;
		default:
			break;
	}
}
?>