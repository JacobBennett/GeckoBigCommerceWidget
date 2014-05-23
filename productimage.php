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
	$product_number = isset($_GET['product']) ? $_GET['product'] : '77';


	$image = Bigcommerce::getProductImages($product_number);

	dd($image);