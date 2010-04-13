<?php

/* Configuration */
require_once("config.php");

header("Content-type: text/plain");

if (!isset($_GET["w"])) exit();

if ( get_magic_quotes_gpc() ) {
	$raw_words = stripslashes($_GET["w"]);
	
} else {
	$raw_words = $_GET["w"];
}

$words = explode(',', $raw_words);

try {
	// MySQL Connexion
    $db = new PDO('mysql:host='.$antony_config["db_hostname"].';dbname='.$antony_config["db_dbname"], $antony_config["db_username"], $antony_config["db_password"]);
} catch(PDOException $e) {
	echo $e->getMessage();
}

$words_antonyms = array();

foreach ($words as $i => $word) {
    
    // Try to fetch the antonym locally
    $st = $db->prepare("SELECT antonym, last_update FROM words_antonyms WHERE word = ?");
    $st->execute(array($word));
    $result = $st->fetch(PDO::FETCH_OBJ);
    
    // Antonym not found locally
    if (!$result) {
        
        // Get the antonym with API
        $api_ant = get_api_antonym($word);
        
        // No antonym? Set the original word as antonym
        if (!$api_ant) {
            $api_ant = $word;
        }
        
        // Insert the new word
        $st = $db->prepare("INSERT INTO words_antonyms (word, antonym) VALUES (:word, :antonym)");
        $st->execute(array(
            ':word' => $word,
        	':antonym' => $api_ant
        ));
        
        $antonym = $api_ant;
        
    // Antonym found locally
    } else {
        $antonym = $result->antonym;
    }
    
    $words_antonyms[$word] = $antonym;
}

echo json_encode($words_antonyms);

// Close connexion
$db = null;

function get_api_antonym($w) {
    
    global $antony_config;
    
    $api_url = 'http://words.bighugelabs.com/api/2/'.
               $antony_config["bht_api_key"] .'/'.
               urlencode($w) .'/php';
    
    $rsp = @file_get_contents($api_url);
    
    if (!$rsp) { return false; }
    
    $rsp_obj = unserialize($rsp);
    
    $ant = false;
    
    // Get the first antonym
    foreach ($rsp_obj as $cat) {
        if (isset($cat["ant"])) {
            $ant = $cat["ant"][0];
            break;
        }
    }
    
    return $ant;
}
