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
	$order_number = $_GET['order'];


	$order = Bigcommerce::getOrder($order_number);

	// dd($order);
	

	// SET UP A RETURN ARRAY 
	$products = array();

	// GO GET THE PICTURES FOR EACH OF THESE ORDERS
	foreach ($order->products as $product) {

		//ONLY SHOW PHYSICAL PRODUCTS
		if($product->type != "physical") continue;

		$array_to_push = array(
			'product_id' => $product->product_id,
			'quantity' => $product->quantity,
			'name' => $product->name,
			'sku' => $product->sku,
			'images' => Bigcommerce::getProductImages($product->product_id)->standard_url
		);

		array_push($products, $array_to_push);

	}

?>

<html>
	<head>
		<title>Order Breakdown</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

		<style>
			body {padding: 1em 0;}
			table img {width: 8em;}
			thead {font-size: 1.5em;}
			tbody, .panel-body {font-size: 1.5em;}
			.completed {background-color: #d9edf7;}
			.completed > td {opacity: 0.5;}
			tbody td:nth-child(3) {font-size: 2em;}
		</style>
	</head>

	<body>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-sm-12">
									<h1 class="pull-left">ORDER #: <?= $order_number ?></h1>
									<h1 class="pull-right">Order Amt: $<?= number_format($order->subtotal_ex_tax,2) ?></h1>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">
								
								<div class="col-sm-6">
									<div class="panel panel-info">
										<div class="panel-heading">
											<h4>Name and Shipping</h4>
										</div>
										<div class="panel-body">
											<?= $order->billing_address->first_name ?> <?= $order->billing_address->last_name ?><br/>
											304 Bonair Drive<br/>
											Normal, IL 61761
										</div>								
									</div>
								</div>

								<div class="col-sm-6">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h4>Shipping Provider & Method</h4>
										</div>
										<div class="panel-body">
											USPS
										</div>								
									</div>
								</div>

							</div>
						</div>

						<table class="table-striped table">
							<thead>
								<tr>
									<th>Image</th>
									<th>Name</th>
									<th>Quantity</th>
									<!-- <th>SKU</th> -->
								</tr>
							</thead>
							<tbody>
								<?php

									foreach ($products as $product) {
									?>

									<tr>
										<td><img src="<?= $product['images']; ?>" alt="<?= $product['product_id']; ?>"/></td>
										<td><?= $product['name']; ?></td>
										<td><?= $product['quantity']; ?></td>
										<!-- <td><?= $product['sku']; ?></td> -->
									</tr>

									<?php
									}

								?>
							</tbody>

						</table>

					</div>
				</div>
			</div>
		</div>
		

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
	<script>
		$('table').on('click', 'tr', function(){
			$(this).toggleClass('completed');
		});
	</script>
	
	</body>
</html>


	