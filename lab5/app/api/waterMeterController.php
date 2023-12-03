<?php

namespace lab5;

session_start();
if (!isset($_SESSION['username'])) {
	echo '{"error":"Unauthorized"}';
	die();
}
require_once('../classes.php');
$wml = new WaterMeterList();
$wml->importFromFile('../../data/water_meters.csv');
$pl = new PropertyList();
$pl->importFromFile('../../data/properties.csv');
echo $wml->convertToJSON();
if (isset($_POST['name'])) {
	$propsArray = [];
	$pDataArray = $pl->getDataArray();
	for ($i = 0; $i < count($pDataArray); $i++) {
		$propsArray += ['' . $pDataArray[$i]->getName() => '' . $_POST['property_' . $pDataArray[$i]->getId()]];
	}

	$wml->add(
		$_POST['name'],
		$_POST['manufacturer'],
		$_POST['price'],
		$propsArray
	);
	$wml->exportToFile('../../data/water_meters.csv');
}
