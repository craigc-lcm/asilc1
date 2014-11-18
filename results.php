<?php
require('./silc_class.php');
$silc = new Silc();

$apr = floatval($_POST['apr']);
$rate = $apr/12;
$number_of_months = floatval($_POST['number_of_months']);
$periodic_payment = floatval($_POST['periodic_payment']);
$down_payment = floatval($_POST['down_payment']);

$data = array(
		'periodic_payment' => $periodic_payment,
		'apr' => $apr,
		'number_of_months' => $number_of_months,
		'principle' => 0,
		'status' => 'ok'
	);

$principle = $silc->get_principle($rate, $number_of_months, $periodic_payment);

$data['periodic_payment'] = number_format($periodic_payment, 0, '.', '');
$data['apr'] = number_format($apr, 4, '.', '');
$data['principle'] = number_format($principle, 0, '.', '');
$data['number_of_months'] = $number_of_months;

$initial_cost = ($principle + $down_payment - 101) / 1.08655;
$data['initial_cost'] = number_format($initial_cost, 0, '.', '');

echo JSON_encode($data);
?>


