<?php

namespace lab5;

session_start();
if (!isset($_SESSION['username'])) {
	echo '{"error":"Unauthorized"}';
	die();
}
require_once('../classes.php');
$pl = new PropertyList();
$pl->importFromFile('../../data/properties.csv');
echo $pl->convertToJSON();
if (isset($_POST['name'])) {
	eval('$valuesArray='.$_POST['values'].';');
	$pl->add($_POST['name'], $valuesArray);
	$pl->exportToFile('../../data/properties.csv');
}
