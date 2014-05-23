<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// COMPOSER AUTOLOAD
require('vendor/autoload.php');

// NAMESPACING
use Bigcommerce\Api\Client as Bigcommerce;

// LOAD IN CREDENTIALS FOR BC
require('credentials.php');

// CONFIGURE BIGCOMMERCE
Bigcommerce::configure($credentials);
Bigcommerce::setCipher('RC4-SHA');

// DEFINE A DIE AND DUMP METHOD
function dd($var) {
	var_dump($var);
	exit;
}

	// SET ORDER TO BE THE GET VAR OR A DEFAULT FOR TESTING
	$order_number = isset($_GET['order']) ? $_GET['order'] : '16690';


	$order = Bigcommerce::getOrder($order_number);
	
	// THIS GIVES ME BACK A COLLECTION OF ALL THE PRODUCTS FOR A PARTICLUAR ORDER
	// dd($order->products);

	// SET UP A RETURN ARRAY 
	$return_array = [];

	// GO GET THE PICTURES FOR EACH OF THESE ORDERS
	foreach ($order->products as $key => $value) {

		$array_to_push = [
			'quantity' => $value->quantity,
			'name' => $value->name,
			'sku' => $value->sku,
			'images' => Bigcommerce::getProductImages($value->product_id)->standard_url
		];

		array_push($return_array, $array_to_push);

		// if type is digital, dont show
		//push all the values you want into this field
	}

	// dd($order);
	// dd($products);
	dd($return_array);