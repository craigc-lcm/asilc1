<?php
class Silc
{
	public function get_payment($principle, $rate, $numPeriods)
	{
		if($rate == 0)
		{
			$periodicPayment = $principle / $numPeriods;
		} 
		else
		{
			$periodicPayment = $principle * ( $rate / ( 1 - pow( (1 + $rate), -$numPeriods ) ) );
		}
		return $periodicPayment;
	}

	public function get_principle($rate = 1, $numPeriods = 0, $periodicPayment = 0)
	{
		if($rate == 0)
		{
			$principle = $periodicPayment * $numPeriods;
		} 
		else
		{
			$principle = $periodicPayment * ( ( 1 - pow( (1 + $rate), -$numPeriods ) )/ $rate );
		}
		return $principle;
	}

	public function get_rate($principle = 1, $numPeriods = 1, $periodicPayment = 0)
	{
		$resolution = 50; //used for the iterations in approximation during "calcRate" mode
		if(($periodicPayment * $numPeriods) < $principle)
		{	
			return -1;//These numbers won't work because you'll be paying back less than you owe 
		}
		elseif(($periodicPayment * $numPeriods) == $principle) 
		{
			return 0;
		}
		else
		{
			$K_numerator;
			$K_denominator;
			$rate = ($periodicPayment / $principle) - (1 / $numPeriods);
			for($j=0; $j<$resolution; $j++)
			{
				$K_numerator = $periodicPayment*(1-pow((1+$rate),-$numPeriods))-$principle*$rate;
				$K_denominator = $periodicPayment*$numPeriods*pow((1+$rate),-$numPeriods-1)-$principle;
				$rate = $rate - $K_numerator/$K_denominator;
			}
			return $rate;
		}
	}

	public function get_num_periods($principle, $rate, $periodicPayment)
	{
		if($rate == 0)
		{
			$numPeriods = $principle / $periodicPayment;
			return $numPeriods;
		} 
		elseif ($principle < $periodicPayment)
		{
			return -1;//If you have this much to pay each month, then why do you need a loan for this principle?
		}
		elseif($principle*$rate > $periodicPayment)
		{
			return -1;//the interest will accumulate faster than you can pay for it, so the loan will never be paid off
		}
		else
		{
			$numPeriods = (log(1-($principle*$rate/$periodicPayment)))/(-log(1+$rate));
			return $numPeriods;
		}
	}

	public function get_total_interest($principle, $periodicPayment, $numPeriods)
	{
		return ($periodicPayment * $numPeriods) - $principle;
	}

	public function get_monthly_amortization_schedule_JSON($p, $apr, $n, $m, $first_payment_date)
	{
		$p = floatval($p);
		$r = floatval($apr)/12;
		$n = floor($n);
		$m = floatval($m);//monthly payment
		$interestPayment = 0;
		$prinPayment = 0;
		$remainingBal = floatval($p);
		$paymentDate = new DateTime($first_payment_date);
		$one_month = new DateInterval('P1M');		
		
		$schedule = array();
		array_push($schedule, array(
				'date' => "",
				'payment' => "",
				'interest' => "",
				'principle' => "",
				'balance' => number_format($p, 2, '.', ',')			
				)
			);
		for($i=0; $i<$n; $i++)
		{
			$interestPayment = $r * $remainingBal;
			$prinPayment = $m - $interestPayment;
			$remainingBal = $remainingBal - $prinPayment;
			if($remainingBal < 0)
			{
				$m += $remainingBal;
				$remainingBal = 0;
			}
			array_push($schedule, array(
					'date' => date('m/d/Y', $paymentDate->getTimeStamp()),
					'payment' => number_format($m, 2, '.', ','),
					'interest' => number_format($interestPayment, 2, '.', ','),
					'principle' => number_format($prinPayment, 2, '.', ','),
					'balance' => number_format($remainingBal, 2, '.', ',')			
					)
				);
			$paymentDate->add($one_month);
		}
		return JSON_encode($schedule);
	}
}
?>
