<?php 
require '../backend/calculationTarif.php';

if (isset($_POST['data'])) {
	echo json_encode($calt->calclTarif($_POST));
}
