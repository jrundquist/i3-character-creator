<?php
/* This variable holds all user information.
 * Set by sess_read() call with the session ID
 * from the cookie.
 *
 * $user->session   session values
 * $usr   !USER ID!
 * 
 */
//global $user;

//require_once("./includes/session.inc");
//session_start();
//ini_set('session.cookie_domain', "testsite.untoldthegame.com");

chdir("../../testsite");
require('includes/bootstrap.inc');
require('includes/common.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//$node = node_load(arg(1));

chdir("../testcb/cb_backend");

$this_user = $user->uid;

/*if(!isset($this_user))
	header("Location: http://testsite.untoldthegame.com/user"); // redirects to production login
else*/
	//echo $this_user;
//	echo "FUCK YEAH";
?>
<html>
<head>
<script type="text/javascript">

function alertCookie() {
	alert(document.cookie);
}

</script>
</head>
<body onload="alertCookie();">
</body>
</html>