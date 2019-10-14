<?php
	session_start();

	// Autoloads classes when needed
	spl_autoload_register(function($class){
	$home = dirname(__FILE__);
	include_once ($home. '\classes\\' . $class . '.class.php');
	});

	// Function to sanitize the user input
	function escape($string){
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="themes/default.theme.css">
	<link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
