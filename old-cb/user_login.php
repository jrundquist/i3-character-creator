<?php
/* This variable holds all user information.
 * Set by sess_read() call with the session ID
 * from the cookie.
 *
 * $user->session   session values
 * $user->uid   !USER ID!
 * 
 */
global $user;

require_once("./includes/session.inc");
//drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//require('../../testsite/includes/bootstrap.inc');
//require('../../testsite/includes/common.inc');

/*if(!$user->uid)
	header("Location: http://www.untoldthegame.com/user"); // redirects to production login
else
	echo "FUCK YEAH";*
?>