<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);
require_once('../models/card.php');


$card = isset($_REQUEST['id'])?new Card($_REQUEST['id']):false;

if ( $card ){
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