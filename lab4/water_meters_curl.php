<?php 
$url = 'http://localhost/labs/lab4/lab4.php';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);

$data = curl_exec($curl);
$dataArray = json_decode($data);
for ($i = 0; $i < count($dataArray); $i++) {
	$result = $dataArray[$i]->id . ". <b>". $dataArray[$i]->name . "</b></br>
    Виробник: " . $dataArray[$i]->manufacturer . "<br>
	Ціна: " . $dataArray[$i]->price . "<br>";
    $result .= '<i>Характеристики:</i></br>';
	foreach ($dataArray[$i]->properties as $key => $value) {
		$result .=  $key . ": " . $value;
		$result .=  "<br>";
	}
	echo $result;
}
curl_close($curl);
