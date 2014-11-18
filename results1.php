<?php
require('./silc_class.php');
$silc = new Silc();

$apr = floatval($_POST['apr']);
$rate = $apr/12;
$number_of_months = floatval($_POST['number_of_months']);
$periodic_payment = floatval($_POST['periodic_payment']);
$sticker_price = floatval($_POST['sticker_price']);

$data = array(
		'periodic_payment' => $periodic_payment,
		'apr' => $apr,
		'number_of_months' => $number_of_months,
		'sticker_price' => $sticker_price,
		'status' => 'ok'
	);

$principle = $silc->get_principle($rate, $number_of_months, $periodic_payment);

$data['periodic_payment'] = number_format($periodic_payment, 0, '.', '');
$data['apr'] = number_format($apr, 4, '.', '');
$data['sticker_price'] = number_format($sticker_price, 0, '.', '');
$data['number_of_months'] = $number_of_months;

$down_payment = $sticker_price*1.08655 + 101 - $principle;
$data['initial_cost'] = number_format($sticker_price, 0, '.', '');
$data['down_payment'] = number_format($down_payment, 0, '.', '');

echo JSON_encode($data);
?>


