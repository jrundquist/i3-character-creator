<?php

header('Access-Control-Allow-Origin: *');

require('includes/bootstrap.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
?>
function get_user(){
	var id = <?
if(isset($user->uid)){
    echo $user->uid;
}
else{
    echo '0';
}
?>;

    if(id == '0')
		window.location = "http://testsite.untoldthegame.com/user";
	else
		return id;
}