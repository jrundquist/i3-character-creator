<?php


set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);


require_once('../models/card.php');


if(isset($_REQUEST['type'])) {
	if(isset($_REQUEST['cardId'])) {
		$card = new Card($_REQUEST['cardId']);
	}
	else {
		$card = False;
	}
	switch($_REQUEST['type']) {
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
				
				echo '
				<h1>'.preg_replace('%\[\d+\]%', '', $toret['listname']).'</h1>
				<div class="card-container">
					<img id="front-card" class="bigImg" src="http://testcb.untoldthegame.com/Version1.3/card_imgs/'.$toret['backimg'].'"/>
				</div>
				<div class="card-container">
					<img id="back-card"  class="bigImg" src="http://testcb.untoldthegame.com/Version1.3/card_imgs/'.$toret['frontimg'].'"/>
				</div>';
				
			}
			break;
		default:
			break;
	}
}
?>