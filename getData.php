<?php

use Test\Classes\Filters\CronJob       as CronJob;
use Elasticsearch\ClientBuilder;

require_once __DIR__ . '/vendor/autoload.php';

$client = ClientBuilder::create()->build();

if (isset($_GET['q'])) { 

	$q = $_GET['q'];

	$query = $client->search([
		'body' => [
					'query' => [ 
								'bool' => [
										'should' => [
												'match' => ['name'  => $q],
												'match' => ['books' => $q]
												]
										]
								]
				]
	]);

	if ($query['hits']['total']['value'] >= 1 )
		$results = $query['hits']['hits'];


}

if($_POST['newIndex']) {

	$params = [
		'index' => 'books',
		'body'  => [
			'settings' => [
				'number_of_shards' => 2,
				'number_of_replicas' => 0
			]
		]
	];
	
	$response = $client->indices()->create($params);
	print_r($response);
}

if($_POST['add']) {
	
	$params = [
		'index' => 'books',
		'body'  => ['name' => 'prima carte',
					'author' => 'Adrian']
	];

	$response = $client->index($params);

	print_r($response);
}

if($_GET['index']) {
	$params = [
		'index' => 'authors',
		'id'    => '1'
	];

	$response = $client->get($params);
	echo "<pre>";
	print_r($response);
}

if($_GET['searchAll']) {

	echo "<pre>";
	$params = [
		'index' => 'authors',
		'body'  => [
			'query' => [
				'match_all' => (object)[]
			]
		]
	];
	
	$response = $client->search($params);
	print_r($response);
}

if($_GET['search']) {

	echo "<pre>";
	$params = [
		'index' => 'authors',
		'body'  => [
			'query' => [
				'match' => [
					'books' => 'end of new'
				]
			]
		]
	];
	
	$response = $client->search($params);
	print_r($response);
}


	 if(isset($_GET['q'])) { // (4)

		$q = $_GET['q'];
		
		$query = $client->search([
			'body' => [
						'query' => [ // (5)
									'bool' => [
											'should' => [
													'match' => ['name'  => $q],
													'match' => ['books' => $q]
													]
											]
									]
					]
		]);

		echo "<pre>";
		var_dump($query['hits']['hits']);
		die();
		if($query['hits']['total'] >=1 ) { // (6)
		$results = $query['hits']['hits'];
		
		}
		
		}
		?>
		
		<!-- HTML STARTS HERE -->
		<!DOCTYPE>
		<html>  
			<head>
				<meta charset="utf-8">
				<title>Search Elasticsearch</title>
				<link rel="stylesheet" href="css/main.css">
			</head>
			<body>
				<form action="index.php" method="get" autocomplete="off">
					<label>
						Search for Something
						<input type="text" name="q">
					</label>
					<input type="submit" value="search">
				</form>
							   
				<div class="res">
					<a href="#id">Name</a>
				</div>
				<div class="res">Attributes</div>
			</body>
		</html>