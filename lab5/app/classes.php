<?php

namespace lab5;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
abstract class BaseList
{
	protected $dataArray;
	protected $index;

	public function getDataArray()
	{
		return $this->dataArray;
	}
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

	public function getTable()
	{
		$tableContent = '';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$tableContent .= $this->dataArray[$i]->getDataAsTableRow();
		}
		return $tableContent;
	}
	public function showAll()
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			echo $this->dataArray[$i]->displayInfo();
		}
	}
	public abstract function importFromFile($fileName);

	public function delete($id)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				array_splice($this->dataArray, $i, 1);
				break;
			}
		}
	}

	public function exportToFile($path)
	{
		if (($handle = fopen($path, "w")) !== FALSE) {
			for ($i = 0; $i < count($this->dataArray); $i++) {
				fwrite($handle, $this->dataArray[$i]->getDataAsCSVRow());
			}
			fclose($handle);
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
	public function getDataAsXML()
	{
		header("Content-type: text/xml");
		$result = '<?xml version="1.0" encoding="UTF-8"?>
		<waterMeters>';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= $this->dataArray[$i]->getDataAsXML();
		}
		$result .= '</waterMeters>';
		return $result;
	}

	public function importFromFile($path)
	{
		$row = 1;
		$propsArray = [];
		if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				eval('$propsArray=' . $data[3] . ';');
				//$propsArray = $data[3];
				$this->add($data[0], $data[1], $data[2], $propsArray);
				$row++;
			}
			fclose($handle);
		}
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

	public function getDataAsCSVRow()
	{
		return '"' . addslashes($this->name) . '","' . addslashes($this->manufacturer) . '","' . addslashes($this->price) . '","' . $this->getPropertiesForCSV() . '"' . "\n";
	}

	public function getDataAsXML()
	{
		return '<waterMeter>
				<id>' . $this->id . '</id>
				<name>' . $this->name . '</name>
				<manufacturer>' . $this->manufacturer . '</manufacturer>
				<price>' . $this->price . '</price>
				<properties>' . $this->getPropertiesForXML() . '</properties>
				</waterMeter>';
	}

	public function getDataAsTableRow()
	{
		return "
			<tr>
				<td>" . $this->id . "</td>
				<td>" . $this->name . "</td>
				<td>" . $this->manufacturer . "</td>
				<td>" . $this->price . "</td>
				<td>" . $this->displayProperties() . "</td>
			</tr>
		";
	}

	public function edit($name, $manufacturer, $price, $properties)
	{
		$this->name = $name;
		$this->manufacturer = $manufacturer;
		$this->price = $price;
		$this->properties = $properties;
	}

	public function getAsJSONObject()
	{
		return get_object_vars($this);
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


class ManufacturerList extends BaseList
{
	public function importFromFile($fileName)
	{
		$row = 1;
		if (($handle = fopen($fileName, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$this->add($data[0]);
				$row++;
			}
			fclose($handle);
		}
	}

	public function getDataAsXML()
	{
		header("Content-type: text/xml");
		$result = '<?xml version="1.0" encoding="UTF-8"?>
		<manufacturers>';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= $this->dataArray[$i]->getDataAsXML();
		}
		$result .= '</manufacturers>';
		return $result;
	}

	public function getDataAsSelect()
	{
		$i = 1;
		$result = "<select name='manufacturer'>";
		$result .= '<option selected disabled hidden>Виберіть виробника</option>';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= $this->dataArray[$i]->getDataAsOption();
		}
		$result .= '</select>';
		return $result;
	}

	public function add($name)
	{
		$id = ++$this->index;
		$nm = new Manufacturer($id, $name);
		array_push($this->dataArray, $nm);
		return $id;
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

	public function getDataAsOption()
	{
		return "<option value='" . $this->name . "'>" . $this->name . "</option>";
	}

	public function getAsJSONObject()
	{
		return get_object_vars($this);
	}

	public function getDataAsCSVRow()
	{
		return '"' . addslashes($this->name) . '"' . "\n";
	}

	public function getDataAsXML()
	{
		return '<manufacturer>
				<id>' . $this->id . '</id>
				<name>' . $this->name . '</name>
				</manufacturer>';
	}

	public function getDataAsTableRow()
	{
		return "
			<tr>
				<td>" . $this->id . "</td>
				<td>" . $this->name . "</td>
			</tr>
		";
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

	public function importFromFile($path)
	{
		$row = 1;
		$valuesArray = [];
		if (($handle = fopen($path, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				eval('$valuesArray=' . $data[1] . ';');
				$this->add($data[0], $valuesArray);
				$row++;
			}
			fclose($handle);
		}
	}

	public function getDataAsSelect()
	{
		$result = "<h3>Характеристики</h3>";
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= "<p><select name='property_" . $this->dataArray[$i]->getId() . "'>";
			$result .= "<option selected disabled hidden>" . $this->dataArray[$i]->getName() . "</option>";
			$result .= $this->dataArray[$i]->getValuesAsOptions();
			$result .= '</select></p>';
		}

		return $result;
	}

	public function getDataAsXML()
	{
		header("Content-type: text/xml");
		$result = '<?xml version="1.0" encoding="UTF-8"?>
			<properties>';
		for ($i = 0; $i < count($this->dataArray); $i++) {
			$result .= $this->dataArray[$i]->getDataAsXML();
		}
		$result .= '</properties>';
		return $result;
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

	public function getName()
	{
		return $this->name;
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

	public function getValuesAsOptions()
	{
		$result = "";
		for ($i = 0; $i < count($this->values); $i++) {
			$result .= "<option value='" . $this->values[$i] . "'>" . $this->values[$i] . "</option>";
		}
		return $result;
	}

	public function getDataAsCSVRow()
	{
		return '"' . addslashes($this->name) . '","' . $this->getValuesForCSV() . '"' . "\n";
	}

	public function getDataAsXML()
	{
		return "
				<property>
					<id>" . $this->id . "</id>
					<name>" . $this->name . "</name>
					<values>" . $this->getValuesForXML() . "</values>
				</property>
			";
	}
	private function getValuesForCSV()
	{
		$result = "[";
		foreach ($this->values as $value) {
			$result .=  "'" . addslashes($value) . "'";
			$result .= ",";
		}
		$result = substr_replace($result, "", -1);
		$result .= "]";
		return $result;
	}
	private function getValuesForXML()
	{
		$result = '';
		foreach ($this->values as $value) {
			$result .= '<property><value>' . $value . '</value></property>';
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
		foreach ($this->values as $value) {
			$result .=  $value;
			$result .=  "<br> ";
		}
		return $result;
	}

	public function getDataAsTableRow()
	{
		return "
				<tr>
					<td>" . $this->id . "</td>
					<td>" . $this->name . "</td>
					<td>" . $this->dipslayValues() . "</td>
				</tr>
			";
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
