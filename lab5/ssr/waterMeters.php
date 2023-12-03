<?php

namespace lab5;


session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}
require_once('../app/classes.php');
$wml = new WaterMeterList();
$wml->importFromFile('../data/water_meters.csv');
$ml = new ManufacturerList();
$ml->importFromFile('../data/manufacturers.csv');
$pl = new PropertyList();
$pl->importFromFile('../data/properties.csv');
if (isset($_POST['name'])) {
	$propsArray = [];
	$pDataArray = $pl->getDataArray();
	for ($i = 0; $i < count($pDataArray); $i++) {
		$propsArray += [''.$pDataArray[$i]->getName() => ''.$_POST['property_'.$pDataArray[$i]->getId()]];
	}

	$wml->add(
		$_POST['name'],
		$_POST['manufacturer'],
		$_POST['price'],
		$propsArray
	);

	$wml->exportToFile('../data/water_meters.csv');
}
?>
<html>

<head>
	<title>Water Meters List</title>
	<link href="../assets/style.css" rel="stylesheet" />
</head>

<body>
	<div class='container'>
		<div class='navigation'>
			<ul>
				<li><a href="waterMeters.php">Лічильники води</a></li>
				<li><a href="manufacturers.php">Виробники</a></li>
				<li><a href="properties.php">Властивості</a></li>
				<li><a href="logout.php">Вийти</a></li>
			</ul>
		</div>
		<div class='table-content'>
			<h1>Лічильники води</h1>
			<table>
				<thead>
					<th>ID</th>
					<th>Модель</th>
					<th>Виробник</th>
					<th>Ціна</th>
					<th>Характеристики</th>
				</thead>
				<tbody>
					<?php echo $wml->getTable(); ?>
				</tbody>
			</table>
		</div>
		<div class='form-content'>
			<form method="POST">
				<p><input type="text" placeholder="Модель" name="name" required /></p>
				<p><?php echo $ml->getDataAsSelect(); ?></p>
				<p><input type="text" placeholder="Ціна" name="price" required /></p>
				<p><?php echo $pl->getDataAsSelect(); ?></p>
				<p><button type="submit">Зберегти</button></p>
			</form>
		</div>
	</div>
</body>

</html>