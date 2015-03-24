<?php
function test_input($inp) {
	$inp = trim($inp);
	$inp = stripslashes($inp);
	$inp = htmlspecialchars($inp);
	return $inp;
}
?>