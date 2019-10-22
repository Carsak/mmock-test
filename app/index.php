<?php


require_once "SimpleParser.php";

$parser = new SimpleParser('Moscow');

$data = $parser->parse();

var_dump($data);

