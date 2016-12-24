<?php
$weather = 'Воскресенье 25 декабря   мин. 0°  макс. -19°    Array';
$pattern = "/\;\s+(\W+\d++\s\W+)['мин.'\s](\+\d++|\-\d++|\d++)|['макс.'\s](\+\d++|\-\d++|\d++)/U";
//$pattern = "/\+\d++/U";

$data = preg_match_all($pattern, $weather, $matches);
echo "<pre>";
print_r($weather);
print_r($matches);
echo "</pre>";