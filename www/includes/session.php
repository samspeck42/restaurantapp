<?php
// start session
session_start();

$userId = $isOwner = $isEmployee = 0;

function is_logged_in() {
	return isset($_SESSION["user_id"]) && 
	isset($_SESSION["is_owner"]) && 
	isset($_SESSION["is_employee"]);
}

function load_session() {
	global $userId, $isOwner, $isEmployee;
	if (is_logged_in()) {
		$userId = $_SESSION["user_id"];
		$isOwner = $_SESSION["is_owner"];
		$isEmployee = $_SESSION["is_employee"];
		return true;
	} else {
		return false;
	}
}

function store_session() {
	global $userId, $isOwner, $isEmployee;
	$_SESSION["user_id"] = $userId;
	$_SESSION["is_owner"] = $isOwner;
	$_SESSION["is_employee"] = $isEmployee;
}
?>