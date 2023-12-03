<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//TASK 3.1
function calcFirstTask($x)
{
	echo "Fisrt task: <br>";
	if ($x > 1) {
		$y = exp(-$x) + abs(pow($x, 2) - 1);
	} else if (-M_PI < $x && $x <= 1) {
		$y = log(sqrt(abs(1 - $x)));
	}

	echo "y = $y if x = $x <br>";
}
//Task 4.1
function calcSecondTask()
{
	echo "Second task: <br>";
	echo "Subtask a: <br>";
	$t = 2.3;
	$dt = 0.8;
	while ($t <= 7.2) {
		$y = pow(cos(pow($t, 2)), 3) / (1.5 * $t + 2);
		echo "y = $y t = $t <br>";
		$t += $dt;
	}
		
	echo "Subtask b: <br>";
	$t = 0;
	$dt = 0.3;
	$n = 5;

	for ($i = 0; $i < $n; $i++) {
		$y = pow(cos(pow($t, 2)), 3) / (1.5 * $t + 2);
		echo "y = $y t = $t <br>";
		$t += $dt;
	}
}
//Task 5.1
function calcThirdTask($m, $l)
{
	echo "Third task: <br>";
	$S = 0;
	for ($i = 4; $i < 16; $i++) {
		$S += (pow($i, 3) - 2 * $i - 3) / ($i + 4);
	}
	echo "S = $S <br>";

	$y = 1;
	for ($n = $m; $n < $l; $n++) {
		$y *= (pow($n, 2) + 2 * $n + 3) / ($n + 3);
	}
	echo "y = $y <br>";
}
function calcFourthTask($B)
{
	echo "Fourth task: <br>";
	$k = 0;
	foreach ($B as $b) {
		if ($b > 0)
			$k++;
	}
	echo "Amount of positive numbers in array = $k";
}

calcFirstTask(2);
calcFirstTask(-1);
calcSecondTask();
calcThirdTask(4,12);
$B = array(5.0, -2.3, -6.9, -1.1, 2.0, 6.6);
calcFourthTask($B);
?>

