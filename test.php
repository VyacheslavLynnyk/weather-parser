<?php
require_once 'parsing.php';
$weather = WeatherRepository::load('world');
$weather->get();
$days_cities = $weather->get();
$updateDate = $weather->getUpdateDate();
$weather->fixIcons();
$unknowIcons = $weather->getUnknownIcons();
echo "<pre>";
print_r($unknowIcons);
print_r($weather);
echo "</pre>";