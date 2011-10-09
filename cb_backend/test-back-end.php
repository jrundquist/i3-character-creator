<html>
	<head>
		<title>Test back-end of CB</title>
	</head>
	<body>
<?php
require_once('card.php');
$newcard = new Card('01-17-7');
print_r($newcard);
echo '<br /><br />';

require_once('deck.php');
$newdeck = new Deck(4321);
print_r($newdeck);
echo '<br /><br />';

require_once('character.php');
$newchar = new Character(123, 'Some name', 42);
echo 'Initial character object: ';
print_r($newchar);
echo '<br /><br />With a card added: ';
$newchar->addCard($newcard);
print_r($newchar);
echo '<br /><br />With a card removed: ';
$newchar->removeCard($newcard);
print_r($newchar);
echo '<br />';
?>
	</body>
</html>