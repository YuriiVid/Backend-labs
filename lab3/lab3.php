<?php 
namespace lab3;
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
	public function delete($id)
	{
		for ($i = 0; $i < count($this->dataArray); $i++) {
			if ($this->dataArray[$i]->getId() == $id) {
				array_splice($this->dataArray, $i, 1);
			}
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
		echo "Data was deleted";
	}
}

$wm1 = new WaterMeter(1, 'ETK-UA 15-110', 'GROSS', 689, [
	'Призначення' => 'Для холодної води', 'Вид' => 'Електронний',
	'Діаметр різьбовго підключення' => '1/2'
]);
echo $wm1->displayInfo();


class ManufacturerList extends BaseList
{
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

	public function displayInfo()
	{
		return $this->id . ". " . $this->name . "</br>";
	}

	public function __destruct()
	{
		echo "Data was deleted";
	}
}
$nm1 = new Manufacturer(1, "GROSS");
echo "<br>" . $nm1->displayInfo();

class PropertyList extends BaseList
{
	public function add($name, $values)
	{
		$id = ++$this->index;
		$np = new Property($id, $name, $values);
		array_push($this->dataArray, $np);
		return $id;
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
		echo "Data was deleted";
	}
}

$np1 = new Property(1, 'Призначення', ['1' => 'Для холодної води', '2' => 'Для гарячої води']);
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
$wml->displayAll();
