<?php

// COMPOSER AUTOLOAD
require('vendor/autoload.php');

// NAMESPACING
use Bigcommerce\Api\Client as Bigcommerce;
use Carbon\Carbon;

// SETUP DAYS TO USE
$now = Carbon::now();
$yesterday = Carbon::now()->subDays(1);
$last_week = Carbon::now()->subWeeks(1);
$last_last_week = Carbon::now()->subWeeks(2);

// LOAD IN CREDENTIALS FOR BC
require('credentials.php');

// CONFIGURE BIGCOMMERCE
Bigcommerce::configure($credentials);
Bigcommerce::setCipher('RC4-SHA');


	// QUERY FOR ORDERS BETWEEN DATES
	$orders = Bigcommerce::getOrders(['min_date_created' => $now->format('Y-m-d')]);

	$orders2 = Bigcommerce::getOrders(['min_date_created' => $yesterday->format('Y-m-d'), 'max_date_created' => $now->format('Y-m-d')]);

	$orders3 = Bigcommerce::getOrders(['min_date_created' => $last_week->format('Y-m-d')]);


	// FUNCTION FOR SUMMING ORDER TOTALS
	function sum($orders)
	{
		$order_totals = 0;

		foreach ($orders as $order) {
			// ADD ORDER AMOUNT WITHOUT TAX AND SHIPPING
			$order_totals += $order->subtotal_ex_tax;
		}

		return $order_totals;
	}


	// RUN SUMS ON ORDERS
	$totals = sum($orders);
	$totals2 = sum($orders2);
	$totals3 = sum($orders3);


	// RETURN DATA FOR EACH WEEK
$output =	json_encode(
				["item" => [
					[
						"value" => $totals,
						"text" => "Today (" . count($orders) . ")"
					],
					[
						"value" => $totals2,
						"text" => "Yesterday (" . count($orders2) . ")"
					],
					[
						"value" => $totals3,
						"text" => "Last Week (" . count($orders3) . ")"
					]
				]]
			);

print_r($output);
		
