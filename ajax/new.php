<?php
session_start();
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);

//Sets the $userid variable.
require_once('ajax/user.php');
require_once('models/character.php');

$char = new Character($userid, '', 0);
$_SESSION['char'] = serialize($char);

echo json_encode(true);

?>