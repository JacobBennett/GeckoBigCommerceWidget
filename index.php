<?php

// COMPOSER AUTOLOAD
require('vendor/autoload.php');

// NAMESPACING
use Bigcommerce\Api\Client as Bigcommerce;
use Carbon\Carbon;

// SETUP DAYS TO USE
$now = Carbon::now();
$last_week = Carbon::now()->subWeeks(1);
$last_last_week = Carbon::now()->subWeeks(2);

// LOAD IN CREDENTIALS FOR BC
require('credentials.php');

// CONFIGURE BIGCOMMERCE
Bigcommerce::configure($credentials);
Bigcommerce::setCipher('RC4-SHA');

	// $count = Bigcommerce::getOrdersCount(['limit' => '5']);


	// QUERY FOR ORDERS BETWEEN DATES
	$orders = Bigcommerce::getOrders(['min_date_created' => $last_week->format('Y-m-d')]);
	$orders2 = Bigcommerce::getOrders(['min_date_created' => $last_last_week->format('Y-m-d'), 'max_date_created' => $last_week->format('Y-m-d')]);


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


	// RETURN DATA FOR EACH WEEK
	print_r([
			"This Week" => [
				"Amount" => $totals,
				"Count" => count($orders)
			],
			"Last Week" => [
				"Amount" => $totals2,
				"Count" => count($orders2)
			]
		]);
