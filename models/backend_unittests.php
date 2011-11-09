<html>
	<head>
		<title>Test back-end of CB</title>
	</head>
	<body>
<?php
$goodtests = 0;
$badtests = 0;

print "<h3>Testing the Card Class.</h3>";
require_once('card.php');
$newcard = new Card('02-25');
$expectedvals = array(
	"id" => "02-25",
	"name" => "Soul 1",
	"cardtype" => 2,
	"cost" => "2",
	"soul" => array("atk" => "1", "def" => "1", "bst" => "1")
);
print "<ul>";
foreach($expectedvals as $exp_key => $exp_val) {
	if($exp_val == $newcard->getStat($exp_key)) {
		if((string)get_class($exp_val) == "Array") {
			print "<li>The soul array of the card object is <font color='green'>correct</font><br />{";
			foreach($exp_val as $key => $val) {
				print "'$key': $val";
			}
			print "}</li>";
			$goodtests++;
		}
		else {
			print "<li>The $exp_key of the card object is <font color='green'>correct</font> as ";
			print_r($exp_val);
			print ".</li>";
			$goodtests++;
		}
	}
	else {
		if((string)get_class($exp_val) == "Array") {
			print "<li>The soul array of the card object is <font color='red'>inccorect</font><br />{";
			foreach($exp_val as $key => $val) {
				print "'$key': $val";
			}
			print"}</li>";
			$badtests++;
		}
		else {
			print "<li>The $exp_key of the card object is <font color='red'>incorrect</font> as $exp_val ";
			print_r($exp_val);
			print ".</li>";
			$badtests++;
		}
	}
}
print "</ul>";
//print_r($newcard);
//echo '<br /><br />';

$uidtotest = 4321;
$testcard_id = '01-17-7';
$testcard = new Card($testcard_id);
print "<h3>Testing the Deck Class with user id $uidtotest.</h3>";
require_once('deck.php');
$newdeck = new Deck($uidtotest);
$expectedvals = array(
	"userid" => 4321,
	"acard" => $testcard
);
print "<ul>";
if($newdeck->getUserID() == $expectedvals["userid"]) {
	print "<li>The user id of the deck object is <font color='green'>correct</font> as ";
	print_r($newdeck->getUserID());
	print "</li>";
	$goodtests++;
}
else {
	print "<li>The user id of the deck object is <font color='red'>incorrect</font> as ";
	print_r($newdeck->getUserID());
	print "</li>";
	$badtests++;
}
if($newdeck->hasCard($testcard->getStat("id"))) {
	print "<li>The deck <font color='green'>has</font> the expected card in it: ";
	$newdeck->getCard($testcard->getStat("id"))->printCardInfo();
	print "</li>";
	$goodtests++;
}
else {
	print "<li>The deck <font color='red'>does not have</font> the expected card in it: ";
	$newdeck->getCard($testcard->getStat("id"))->printCardInfo();
	print "</li>";
	$badtests++;
}
print "</ul>";
//print_r($newdeck);
//echo '<br /><br />';

print "<h3>Testing the Character Class with a few cards.</h3>";
$ourup = 42;
$charname = 'A name';
require_once('character.php');
$newchar = new Character($uidtotest, $charname, $ourup);
echo 'Initial character object: ';
$newchar->printCharInfo();
echo '<br /><br />Adding card: ';
$newcard->printCardInfo();
$newchar->addCard($newcard);
$newchar->saveCharacter();
//$newchar->printCharInfo();
echo '<br /><br />Adding a second card: ';
$testcard->printCardInfo();
$newchar->addCard($testcard);
$newchar->saveCharacter();
echo '<br /><br />Resulting character: ';
$newchar->printCharInfo();
echo '<br /><br />Removed a card: ';
$newchar->removeCard($newcard);
$newchar->saveCharacter();
$newchar->printCharInfo();
echo '<br /><br />Get a copy of the character from the DB';
$charcopy = new Character($uidtotest, '', 0);
$charcopy->getCharacter($newchar->getCharID(), $uidtotest);
echo '<br /><br />Comparing the copy to the original: ';
//$newchar->printCharInfo();
$newchar_str = $newchar->strRepresentation();
//$charcopy->printCharInfo();
$charcopy_str = $charcopy->strRepresentation();
if($newchar_str == $charcopy_str) {
	//Then the original and copy are the same.
	print "<br />The two character objects <font color='green'>match</font> as expected - <br />";
	$newchar->printCharInfo();
	$goodtests++;
}
else {
	print "<br />The original object <font color='red'>does not</font> match the copy of the object as expected.<br />";
	$newchar->printCharInfo();
	print "<br /><br />";
	$charcopy->printCharInfo();
	$badtests++;
}
echo '<br /><br />Deleting the character from the DB';
$newchar->delCharacter();
echo '<br />';
$totaltests = $goodtests + $badtests;
echo "<font color='green'>$goodtests/$totaltests succeeded</font><br />";
echo "While <font color='red'>$badtests/$totaltests failed</font>.";
?>
	</body>
</html>