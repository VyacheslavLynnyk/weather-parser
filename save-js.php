<?php

require_once __DIR__ . '/parsing.php';

if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'js_files')) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . 'js_files');
}
if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'js_tmp')) {
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . 'js_tmp');
}

//
//ini_set("display_errors", 1);

if (!isset($_COOKIE['load_in'])) {
    exit;
}

$mode = trim(strtolower(strip_tags($_COOKIE['load_in'])));

$weather = WeatherRepository::load($mode);

$weather->get();
$days_cities = $weather->get();

if ($weather->getUpdateDate() == null) {
    echo 'Нужно обновить данные';
    exit;
}


if ($mode == 'ukraine') {
    //function genJS($cities)
    require_once __DIR__ . '/etc.ukraine.php';

} elseif ($mode == 'world') {
    //function genJS($cities)
    require_once __DIR__ . '/etc.world.php';
}

function genPathByDay($n, $mode)
{
    --$n;
    $newDate = date('Y-m-d', strtotime(date('Y-m-d') . "+" . $n . " days"));
    $jsFile = '.' . DIRECTORY_SEPARATOR . 'js_files' . DIRECTORY_SEPARATOR . 'Weath_' . $mode . '_' . $newDate . '.jsx';
    return $jsFile;
}


// Make temporary zip archive
$file = tempnam("js_tmp", "zip");
$zip = new ZipArchive();
$zip->open($file, ZipArchive::OVERWRITE);


// day == 1 is a current day

// Put files with AE scripts into archive
foreach ($days_cities as $day => $cities) {
    $js_path = genPathByDay($day, $mode);
    $links[$day] = $js_path;
    // $zip->addFromString('file_name_within_archive.ext', $your_string_data);
    file_put_contents($js_path, genJS($cities));
    $zip->addFile(
        $js_path,
        str_replace('.' . DIRECTORY_SEPARATOR . 'js_files' . DIRECTORY_SEPARATOR, '', $js_path)
    );

}
$zip->close();

// Download archive
header('Content-Type: application/zip');
header('Content-Length: ' . filesize($file));
header('Content-Disposition: attachment; filename="Weather_' . date('Y-m-d') . '_jsx.zip"');
readfile($file);
unlink($file);


// Prepare File


// Stuff with content


// Close and send to users


// echo $content;
