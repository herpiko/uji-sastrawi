<?php
// demo.php
//
// include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';
// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();
// stem
$sentence = $_GET["input"];
$output   = $stemmer->stem($sentence);
echo $output;
