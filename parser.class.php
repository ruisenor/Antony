<?php

class Antony_Parser {
  
  private $orig_text;
  
  public function __construct($text) {
    $this->orig_text = $text;
  }
  
  /* Returns the text with antonyms replaced */
  public function get_antonymized_text() {
    
    $words_to_translate = $this->get_orig_words();
    $words_to_translate = $this->array_to_lower($words_to_translate);
    
    $aQuery = new Antony_Query();
    
    $antonyms = $aQuery->get_antonyms($words_to_translate);
    
    if (empty($antonyms)) {
      return $this->orig_text;
    }
    
    return $this->stro_replace($words_to_translate, $antonyms, strtolower($this->orig_text));
  }
  
  /* Returns words from the original text */
  private function get_orig_words() {
    $matches;
    preg_match_all("/([a-zA-Z]+)/", $this->orig_text, $matches);
    return $matches[0];
  }
  
  /* str_replace, but operates only on the original string : http://www.php.net/manual/en/function.str-replace.php#88569 */
  private function stro_replace($search, $replace, $subject) {
    return strtr( $subject, array_combine($search, $replace) );
  }
  
  /* Converts an array of strings to lowercase */
  private function array_to_lower($array) {
    foreach ($array as $i => $value) {
      $array[$i] = strtolower($value);
    }
    return $array;
  }
}
