<?php
$params = require __DIR__ . '/params.php';

try {
    	// make a database connection
    	$dbRead  = new PDO($params['dsnRead'], $params['db_user'],$params['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        $dbWrite = new PDO($params['dsnWrite'], $params['db_user'],$params['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);

    } catch (PDOException $e) {
    	die($e->getMessage());
    } 