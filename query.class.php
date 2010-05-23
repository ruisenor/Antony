<?php

class Antony_Query {
  
  private $db;
  private $antony_config;
  
  public function __construct() {
    global $antony_config;
    
    $this->antony_config = $antony_config;
    
    try {
      // MySQL Connexion
      $this->db = new PDO('mysql:host='.$this->antony_config["db_hostname"].';dbname='.$this->antony_config["db_dbname"], $this->antony_config["db_username"], $this->antony_config["db_password"]);
      
    } catch(PDOException $e) {
      echo $e->getMessage();
    }
  }
  
  /* Returns an array of antonyms */
  public function get_antonyms($words) {
    
    $antonyms = array();
    $cached_words = $this->get_from_cache($words);
    
    foreach ($cached_words as $i => $cached_word) {
      
      // Word not cached
      if ($cached_word === FALSE) {
        
        $api_antonym = $this->get_from_api($words[$i]);
        
        // No antonym? Set the original word as antonym
        if (!$api_antonym) {
          $antonyms[$i] = $words[$i];
          
        } else {
          $antonyms[$i] = $api_antonym;
        }
        
        // Insert into cache
        $this->insert_into_cache($words[$i], $antonyms[$i]);
        
      } else {
        $antonyms[$i] = $cached_word;
      }
    }
    
    return $antonyms;
  }
  
  /* Fetch antonyms from database */
  private function get_from_cache($words) {
    $st = $this->db->prepare("SELECT antonym, last_update FROM words_antonyms WHERE word = ?");
    $results = array();
    
    foreach ($words as $word) {
      $st->execute(array($word));
      $res = $st->fetch(PDO::FETCH_OBJ);
      
      if ($res !== FALSE) {
        $res = $res->antonym;
      }
      
      $results[] = $res;
    }
    
    return $results;
  }
  
  /* Fetch an antonym from API */
  private function get_from_api($word) {
    $api_url = 'http://words.bighugelabs.com/api/2/'.
               $this->antony_config["bht_api_key"] .'/'.
               urlencode($word) .'/php';
    
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
  
  /* Insert an antonym into database */
  private function insert_into_cache($word, $antonym) {
    $st = $this->db->prepare("INSERT INTO words_antonyms (word, antonym) VALUES (:word, :antonym)");
    $st->execute(array(
      ':word' => $word,
      ':antonym' => $antonym
    ));
  }
}
