<?php

require_once 'config.php';

function connect($host, $db, $username, $password)
{
	$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

	try {
		$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

		return new PDO($dsn, $username, $password, $options);
	} catch (PDOException $e) {
		die($e->getMessage());
	}
}

return connect($host, $db, $username, $password);

?>  