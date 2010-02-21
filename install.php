<?php

/* Get config */
require_once("config.php");


try {
    $db = new PDO('mysql:host='.$antony_config["db_hostname"].';dbname='.$antony_config["db_dbname"], $antony_config["db_username"], $antony_config["db_password"]);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->exec("CREATE TABLE `antony`.`words_antonyms` (
					`word` VARCHAR( 100 ) NOT NULL ,
					`antonym` VARCHAR( 100 ) NOT NULL ,
					`last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY ( `word` )
				) ENGINE = MYISAM ;");
	
	echo 'Antony was succesfully installed, you can now remove the install.php file.';
    
    $db = null;

} catch(PDOException $e) {
	echo "MySQL Error : ". $e->getMessage();
}