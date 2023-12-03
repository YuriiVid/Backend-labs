<?php

namespace lab5;

session_start();
if (!isset($_SESSION['username'])) {
	header('Location: login.php');
}
require_once('../app/classes.php');
$ml = new ManufacturerList();
$ml->importFromFile('../data/manufacturers.csv');
if (isset($_POST['name'])) {
	$ml->add($_POST['name']);
	$ml->exportToFile('../data/manufacturers.csv');
}
?>
<html>

<head>
	<title>Manufacturers List</title>
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
			<h1>Виробники</h1>
			<table>
				<thead>
					<th>ID</th>
					<th>Назва</th>
				</thead>
				<tbody>
					<?php echo $ml->getTable(); ?>
				</tbody>
			</table>
		</div>
		<div class='form-content'>
			<form method="POST">
				<p><input type="text" placeholder="Назва" name="name" required /></p>
				<p><button type="submit">Зберегти</button></p>
			</form>
		</div>
	</div>
</body>

</html>