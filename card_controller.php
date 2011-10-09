<?php
require_once('cb_backend/card.php');
if(isset($_GET['type']) || isset($_POST['type'])) {
	$command = isset($_GET['type'])?$_GET['type']:$_POST['type'];
	if(isset($_POST['cardid'])) {
		$card = new Card($_POST['cardid']);
	}
	else {
		$card = False;
	}
	switch($command) {
		case 'get_cardlistname':
			if(!$card) {
				echo json_encode("failure");
			}
			else {
				$namestr = createCardListName($card);
				echo json_encode(array("id" => $card->getStat("id"), "str" => $namestr));
			}
			break;
		case 'get_cardimg':
			if(!$card) {
				echo json_encode("failure");
			}
			else {
				echo json_encode($card->getStat("picture"));
			}
			break;
		case 'get_cardbackimg':
			if(!$card) {
				echo json_encode("failure");
			}
			else {
				echo json_encode($card->getStat("backpic"));
			}
			break;
		case 'get_cardtype':
			if(!$card) {
				echo json_encode("failure");
			}
			else {
				echo json_encode($card->getTypeAsStr());
			}
			break;
		case 'get_typeimgsname':
			if(!$card) {
				echo json_encode("failure");
			}
			else {
				$toret = array(
					"frontimg" => $card->getStat("picture"),
					"backimg"  => $card->getStat("backpic"),
					"listname" => createCardListName($card),
					"cardtype" => $card->getTypeAsStr()
				);
				echo json_encode($toret);
			}
			break;
		default:
			break;
	}
}
?>