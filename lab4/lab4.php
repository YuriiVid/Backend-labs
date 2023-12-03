<?php

namespace lab4;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
abstract class BaseList
{
	protected $dataArray;
	protected $index;
	public function __construct()
	{
		$this->dataArray = [];
		$this->index = 0;
	}
	public function convertToJSON()
	{
		header("Content-Type: application/json");
		header('Content-type: text/plain; charset=utf-8');
		$jsonArray = [];
		for ($i = 0; $i < count($this->dataArray); $i++) {
			array_push($jsonArray, $this->dataArray[$i]->getAsJSONObject());
		}
		return json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
	}
	abstract function readFromFile($path);
	abstract function convertToXML();

	public function delete($id)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				array_splice($this->dataArray, $i, 1);
			}
		}
	}

	public function exportToFile($path)
	{
		if (($handle = fopen($path, "w")) !== FALSE) {
			for ($i = 0; $i < count($this->dataArray); $i++) {
				fwrite($handle, $this->dataArray[$i]->getAsCSV());
			}
			fclose($handle);
		}
	}

	public function displayAll()
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			echo $this->dataArray[$i]->displayInfo();
		}
	}
}

class WaterMeterList extends BaseList
{
	public function add($name, $manufacturer, $price, $properties)
	{
		$id = ++$this->index;
		$nwm = new WaterMeter($id, $name, $manufacturer, $price, $properties);
		array_push($this->dataArray, $nwm);
		return $id;
	}

	public function readFromFile($path)
	{
		$row = 1;
		$propsArray = [];
		if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				eval('$propsArray=' . $data[3] . ';');
				//$propsArray = $data[3];
				$this->add($data[0], $data[1], $data[2], $propsArray);
			}
			fclose($handle);
		}
	}

	public function convertToXML()
	{
		header("Content-Type: text/xml");
		$XMLString = '';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$XMLString .= $this->dataArray[$i]->getAsXML();
		}
		return '<?xml version="1.0" encoding="UTF-8"?><waterMeters>' . $XMLString
			. '</waterMeters>';
	}

	public function edit($id, $name, $manufacturer, $price, $properties)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				$this->dataArray[$i]->edit($name, $manufacturer, $price, $properties);
			}
		}
	}
}

class WaterMeter
{
	private $id;
	private $name;
	private $manufacturer;
	private $price;
	private $properties;

	public function getId()
	{
		return $this->id;
	}

	public function __construct($id, $name, $manufacturer, $price, $properties)
	{
		$this->id = $id;
		$this->name = $name;
		$this->manufacturer = $manufacturer;
		$this->price = $price;
		$this->properties = $properties;
	}

	public function getAsCSV()
	{
		return '"' . $this->name . '","' . $this->manufacturer . '","' . $this->price . '","' . $this->getPropertiesForCSV() . '"' . "\n";
	}
	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}
	public function getAsXML()
	{
		return '<waterMeter>
				<id>' . $this->id . '</id>
				<name>' . $this->name . '</name>
				<manufacturer>' . $this->manufacturer . '</manufacturer>
				<price>' . $this->price . '</price>
				<properties>' . $this->getPropertiesForXML() . '</properties>
				</waterMeter>';
	}
	private function getPropertiesForCSV()
	{
		$result = "[";
		foreach ($this->properties as $key => $value) {
			$result .=  "'" . addslashes($key) . "' => '" . addslashes($value) . "'";
			$result .= ",";
		}
		$result = substr_replace($result, "", -1);
		$result .= "]";
		return $result;
	}
	private function getPropertiesForXML()
	{
		$result = '';
		foreach ($this->properties as $key => $value) {
			$result .= '<property><key>' . $key . '</key><value>' . $value . '</value></property>';
		}
		return $result;
	}

	public function edit($name, $manufacturer, $price, $properties)
	{
		$this->name = $name;
		$this->manufacturer = $manufacturer;
		$this->price = $price;
		$this->properties = $properties;
	}

	private function displayProperties()
	{
		$result = '<i>Характеристики:</i></br>';
		foreach ($this->properties as $key => $value) {
			$result .=  $key . ": " . $value;
			$result .=  "<br>";
		}
		return $result;
	}

	public function displayInfo()
	{
		return $this->id . ". <b>" . $this->name . " " . $this->manufacturer . "</b></br>
		Ціна: " . $this->price . "<br>"
			. $this->displayProperties();
	}
	public function __destruct()
	{
		echo "";
	}
}

/*$wm1 = new WaterMeter(1, 'ETK-UA 15-110', 'GROSS', 689, [
	'Призначення' => 'Для холодної води', 'Вид' => 'Електронний',
	'Діаметр різьбовго підключення' => '1/2'
]);
echo $wm1->displayInfo();*/


class ManufacturerList extends BaseList
{
	public function add($name)
	{
		$id = ++$this->index;
		$nm = new Manufacturer($id, $name);
		array_push($this->dataArray, $nm);
		return $id;
	}

	public function readFromFile($path)
	{
		$row = 1;
		if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$this->add($data[0]);
			}
			fclose($handle);
		}
	}
	public function convertToXML()
	{
		header("Content-Type: text/xml");
		$XMLString = '';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$XMLString .= $this->dataArray[$i]->getAsXML();
		}
		return '<?xml version="1.0" encoding="UTF-8"?><manufacturers>' . $XMLString
			. '</manufacturers>';
	}

	public function edit($id, $name)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				$this->dataArray[$i]->edit($name);
			}
		}
	}
}
class Manufacturer
{
	private $id;
	private $name;

	public function getId()
	{
		return $this->id;
	}

	public function edit($name)
	{
		$this->name = $name;
	}

	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}

	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}

	public function getAsCSV()
	{
		return '"' . $this->name . '"' . "\n";
	}

	public function getAsXML()
	{
		return '<manufacturer>
				<id>' . $this->id . '</id>
				<name>' . $this->name . '</name>
				</manufacturer>';
	}

	public function displayInfo()
	{
		return $this->id . ". " . $this->name . "</br>";
	}

	public function __destruct()
	{
		echo "";
	}
}
/*$nm1 = new Manufacturer(1, "GROSS");
echo "<br>" . $nm1->displayInfo();*/

class PropertyList extends BaseList
{
	public function add($name, $values)
	{
		$id = ++$this->index;
		$np = new Property($id, $name, $values);
		array_push($this->dataArray, $np);
		return $id;
	}

	public function readFromFile($path)
	{
		$row = 1;
		$valuesArray = [];
		if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				eval('$valuesArray=' . $data[1] . ';');
				$this->add($data[0], $valuesArray);
			}
			fclose($handle);
		}
	}
	public function convertToXML()
	{
		header("Content-Type: text/xml");
		$XMLString = '';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$XMLString .= $this->dataArray[$i]->getAsXML();
		}
		return '<?xml version="1.0" encoding="UTF-8"?><properties>' . $XMLString
			. '</properties>';
	}

	public function edit($id, $name, $values)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				$this->dataArray[$i]->edit($name, $values);
			}
		}
	}
}

class Property
{
	private $id;
	private $name;
	private $values;

	public function getId()
	{
		return $this->id;
	}

	public function edit($name, $values)
	{
		$this->name = $name;
		$this->values = $values;
	}

	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}

	public function getAsCSV()
	{
		return '"' . $this->name . '","' . $this->getValuesForCSV() . '"' . "\n";
	}

	public function getAsXML()
	{
		return '<property>
				<id>' . $this->id . '</id>
				<name>' . $this->name . '</name>
				<values>' . $this->getValuesForXML() . '</values>
				</property>';
	}
	private function getValuesForCSV()
	{
		$result = "[";
		foreach ($this->values as $key => $value) {
			$result .=  "'" . addslashes($key) . "' => '" . addslashes($value) . "'";
			$result .= ",";
		}
		$result = substr_replace($result, "", -1);
		$result .= "]";
		return $result;
	}
	private function getValuesForXML()
	{
		$result = '';
		foreach ($this->values as $key => $value) {
			$result .= '<property><key>' . $key . '</key><value>' . $value . '</value></property>';
		}
		return $result;
	}

	public function __construct($id, $name, $values)
	{
		$this->id = $id;
		$this->name = $name;
		$this->values = $values;
	}
	private function dipslayValues()
	{
		$result = '<i>Значення:</i></br>';
		foreach ($this->values as $key => $value) {
			$result .=  $key . ") " . $value;
			$result .=  "<br> ";
		}
		return $result;
	}

	public function displayInfo()
	{
		return $this->id . ". " . $this->name . "<br>" . $this->dipslayValues() . "</br>";
	}

	public function __destruct()
	{
		echo "";
	}
}

$wml = new WaterMeterList();
$wml->readFromFile('water_meters.csv');
echo $wml->convertToJSON();

/*$np1 = new Property(1, 'Призначення', ['1' => 'Для холодної води', '2' => 'Для гарячої води']);
echo "<br>" . $np1->displayInfo();

$ml = new ManufacturerList();
$ml->add('GROSS');
$ml->add('BMeters');
$ml->displayAll();
echo '</br>';
$pl = new PropertyList();
$pl->add('Призначення', ['1' => 'Для холодної води', '2' => 'Для гарячої води']);
$pl->add('Вид', ['1' => 'Електронний', '2' => 'Механічний']);
$pl->displayAll();
echo '</br>';
$wml = new WaterMeterList();
$wml->add('ETW-UA 15-110', 'GROSS', '695', ['Призначення' => 'Для гарячої води', 'Вид' => 'Механічний']);
$wml->add('GSD8-I R100', 'BMeters', '781', ['Призначення' => 'Для холодної води', 'Вид' => 'Механічний']);
$wml->displayAll();
echo '</br>';
$wml->edit(2, 'GSD8-I R100', 'BMeters', '781', ['Призначення' => 'Для холодної води', 'Вид' => 'Електронний']);
$wml->displayAll(); */