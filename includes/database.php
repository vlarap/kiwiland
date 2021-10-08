<?php
  try{
		$sql_DB = new PDO('mysql:host='. SQL_SERVER.';dbname='. SQL_DATABASE.';charset=utf8',
						SQL_USERNAME,
						SQL_PASSWORD,
						array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
								PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
						);
		//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
		echo "ERROR: " . $e->getMessage();
	}
?>
