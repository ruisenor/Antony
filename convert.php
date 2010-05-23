<?php

/* Configuration */
require_once("config.php");

/* Antony classes */
require_once("query.class.php");
require_once("parser.class.php");

header("Content-type: text/plain");

if (!isset($_GET["t"])) exit();

if ( get_magic_quotes_gpc() ) {
  $raw_text = stripslashes($_GET["t"]);

} else {
  $raw_text = $_GET["t"];
}

if ($raw_text === "") exit();

$ap = new Antony_Parser($raw_text);

echo $ap->get_antonymized_text();


