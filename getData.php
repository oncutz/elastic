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
												'match' => ['name'  => $q]
												]
										]
								]
				]
	]);

	if ($query['hits']['total']['value'] >= 1 )
			echo(json_encode($query['hits']['hits'][0]['_source']));
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
