<?php
// Task 1.1
$x = 2;
$c = 4;

$S = log(($c * exp(-2.5 * $c + $x) + pow(atan(abs($c - $x)), 2)) / (abs(pow(-1, -2.5 * $c) + sqrt(abs(log(abs($x) + log(abs($c))))))), $c);
echo "Task 1.1: <br> S = $S<br>";

//Task 1.2
$a = 3;
$b = 0;

$result = 6.4 < sqrt($a) && ($b < 2 * $a && 2 * $a <= 8);
$res_out = $result ? 'true' : 'false';
echo "Task 1.2: <br> a = $a, b = $b <br>";
echo "Expression is $res_out <br>";

$a = null;
$b = null;
$x = null;

//Task 2.1
$a = -2.004;
$b = 0.87;
$y = pow(atan(1 / $b),3);
$x = pow(pow($a,2)+pow($b,2),-4.1);
$p=(exp(-$x*$y)+17.4)/(pow(pow(sin($x*$y),2),1/3));
echo "Task 2.1: <br> y = $y, x = $x, p = $p";
?>