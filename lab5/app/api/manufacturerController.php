<?php

namespace lab5;

session_start();
if (!isset($_SESSION['username'])) {
	echo '{"error":"Unauthorized"}';
	die();
}
require_once('../classes.php');
$ml = new ManufacturerList();
$ml->importFromFile('../../data/manufacturers.csv');
echo $ml->convertToJSON();
if (isset($_POST['name'])) {
	$ml->add($_POST['name']);
	$ml->exportToFile('../../data/manufacturers.csv');
}
