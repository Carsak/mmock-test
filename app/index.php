<?php


require_once "Forecast.php";

$forecast = new Forecast('Moscow');

$data = $forecast->findOut();

