<?php
require_once "sdbh.php";
class CalculationTarif extends sdbh
{
	protected $bd;
	function __construct()
	{
		$this->bd = new sdbh();
	}
	public function calclTarif($data)
	{
		$result = [];
		parse_str($data['data'], $parsDataArray);

		$selectProductById = $this->bd->make_query("SELECT * FROM `a25_products` WHERE id = ".$parsDataArray['product']);
		if (empty($selectProductById)) {
		 	return ['errors' => 'Извинте что то пошло не так, попробуйте позже'];
		}

		// Получение выбранного количества дней
		$selectedDays = intval($parsDataArray['days']);
		$result['days'] = $selectedDays;
		$result['title'] = $selectProductById[0]['NAME'];
		$totalSumService = 0;

		foreach ($parsDataArray as $key => $value) {
		  if (strpos($key, "dop-") === 0) {

		    $serviceCost = intval($value) * $selectedDays;
		    $totalSumService += $serviceCost;
		    $result[$key] = $value;
		  }
		}

		if (!isset($selectProductById[0]['TARIFF'])) {
		 	$result['price'] = intval($selectProductById[0]['PRICE']) * $selectedDays + $totalSumService;
		 	$result['oldPrice'] = $selectProductById[0]['PRICE'];
		} else {
			$arrayTarif = unserialize($selectProductById[0]['TARIFF']);
			$selectedPrice = null;

			foreach ($arrayTarif as $days => $price) {
			  if ($selectedDays >= $days) {
			    $selectedPrice = $price;
			    $result['tarif'] = $days;
			    $result['tarifPrice'] = $price;
			    $result['oldPrice'] = $selectProductById[0]['PRICE'];
			  } else {
			    break;
			  }
			}
			$result['price'] = $selectedPrice * $selectedDays + $totalSumService;
		}
		return $result;
	}
}

$calt = new CalculationTarif();