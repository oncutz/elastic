<?php

use Test\Classes\Filters\CronJob as CronJob;
use Test\Classes\Models\Authors  as Authors;
use Elasticsearch\ClientBuilder  as ClientBuilder;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Here we start the process of searching after the xml files, 
 * creating an array and inserting the data into the DB
 */
new CronJob($dbRead, $dbWrite, $params);

/**
 * Preparing the model for Elasticsearch operations
 */
$elastic = ClientBuilder::create()->build();

/**
 * Check if the main indexes are created in Elasticsearch
 */
foreach ($params['indexes'] as $index => $values) {
	if(!$elastic->indices()->exists(['index'=> $index])) {
		$elastic->indices()->create($values);
	}

	// deleteAllEntriesInBothIndexes($elastic, $index);
}


/**
 * Getting the actual data from the DB in form of an array $author => (array)$books
 */
$authors = new Authors($books = [], $dbRead, $dbWrite);
$authorsAndTheirBooks = $authors->getRecords();

/**
 * sending the array to Elasticsearch
 */
upsert($authorsAndTheirBooks, $elastic);

/**
 * In case we need to clear the content of records under one index
 */
function deleteAllEntriesInBothIndexes($elastic, $index) : void
{

	$data = [
		'index' => $index,
		'body' => [
			'query' => [
				'match_all' => (object)[]
			]
		]
	];

	$elastic->deleteByQuery($data);
}

/**
 * We send the array organised as $author => $books and request 
 * to update in ElasticSearch the records based on  the id of the author 
 * or to insert it if there is none
 */
function upsert(array $results, $elasticsearch) : void
{
	foreach($results as $author) {

		$params = [
			'index' => 'authors',
			'id'    => $author['author_id'],
			'body'  => [
				'script' => [
					'source' => "ctx._source.books = params.books; ctx._source.name = params.name",
					'params' => [
						'name' => $author['name'],
						'books' => explode("," , $author['books_name'])
					],
				],
				'upsert' => [
					'name'  => $author['name'],
					'books' => explode("," , $author['books_name'])
				],
			]
		];
		
		$response = $elasticsearch->update($params);
		
	}


}
