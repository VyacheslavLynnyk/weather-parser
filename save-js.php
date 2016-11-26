<?php

require_once __DIR__ . '/parsing.php';

if (!is_dir(__DIR__. DIRECTORY_SEPARATOR . 'js_files')) {
   mkdir( __DIR__. DIRECTORY_SEPARATOR . 'js_files');
}
if (!is_dir(__DIR__. DIRECTORY_SEPARATOR . 'js_tmp')) {
   mkdir( __DIR__. DIRECTORY_SEPARATOR . 'js_tmp');
}

//
//ini_set("display_errors", 1);

$date = date('Y-m-d');
$weather = WeatherRepository::load();

$weather->get();
//$days_cities = $weatherArray['weatherByDate'];
$days_cities = $weather->get();

//if (!isset($weatherArray['date'])) {
if ($weather->getUpdateDate() == null) {
    echo 'Нужно обновить данные';
    exit;
}

// Get current date

function genPathByDay($n) {
    --$n;
    $date = date('Y-m-d');
    $date1 = str_replace('-', '/', $date);
    $newDate = date('Y-m-d',strtotime($date1 . "+".$n." days"));
    $jsFile = '.'. DIRECTORY_SEPARATOR . 'js_files'. DIRECTORY_SEPARATOR .'Weather_'.$newDate.'.jsx';
    return $jsFile;
}

function genJS($cities){
$currentData = date('Y-m-d H:i');
$script = <<<"INFO"
// ---- INFO ----- DATE-UPDATE: {$currentData};
var oblasti = {
    centr : {
        vinnitsa :  {name : 'Вінниця', nt : '{$cities['vinnitsa']['night_t']}', dt : '{$cities['vinnitsa']['day_t']}', sep : '...' , icon : '{$cities['vinnitsa']['icon_chars']}'},
        dnepropetrovsk :  {name : 'Дніпро', nt : '{$cities['dnepropetrovsk']['night_t']}', dt : '{$cities['dnepropetrovsk']['day_t']}', sep : '...' , icon : '{$cities['dnepropetrovsk']['icon_chars']}'},
        kirovograd :         {name : 'Кропивницький', nt : '{$cities['kirovograd']['night_t']}', dt : '{$cities['kirovograd']['day_t']}', sep : '...' , icon : '{$cities['kirovograd']['icon_chars']}'},
        poltava :              {name : 'Полтава', nt : '{$cities['poltava']['night_t']}', dt : '{$cities['poltava']['day_t']}', sep : '...' , icon : '{$cities['poltava']['icon_chars']}'},
        cherkasy :           {name : 'Черкаси', nt : '{$cities['cherkasy']['night_t']}', dt : '{$cities['cherkasy']['day_t']}', sep : '...' , icon : '{$cities['cherkasy']['icon_chars']}'},
    },
    zapad : {
       lviv :                       {name : 'Львів', nt : '{$cities['lviv']['night_t']}', dt : '{$cities['lviv']['day_t']}', sep : '...' , icon : '{$cities['lviv']['icon_chars']}'},
       'ivano-frankivsk' :    {name : 'Івано- Франківськ', nt : '{$cities['ivano-frankivsk']['night_t']}', dt : '{$cities['ivano-frankivsk']['day_t']}', sep : '...' , icon : '{$cities['ivano-frankivsk']['icon_chars']}'},
       rivne :                 {name : 'Рівне', nt : '{$cities['rivne']['night_t']}', dt : '{$cities['rivne']['day_t']}', sep : '...' , icon : '{$cities['rivne']['icon_chars']}'},
       chernivtsy :             {name : 'Чернівці', nt : '{$cities['chernivtsy']['night_t']}', dt : '{$cities['chernivtsy']['day_t']}', sep : '...' , icon : '{$cities['chernivtsy']['icon_chars']}'},       
       lutsk :                     {name : 'Луцьк', nt : '{$cities['lutsk']['night_t']}', dt : '{$cities['lutsk']['day_t']}', sep : '...' , icon : '{$cities['lutsk']['icon_chars']}'},
       ternopol :                {name : 'Тернопіль', nt : '{$cities['ternopol']['night_t']}', dt : '{$cities['ternopol']['day_t']}', sep : '...' , icon : '{$cities['ternopol']['icon_chars']}'},
       uzhgorod :              {name : 'Ужгород', nt : '{$cities['uzhgorod']['night_t']}', dt : '{$cities['uzhgorod']['day_t']}', sep : '...' , icon : '{$cities['uzhgorod']['icon_chars']}'},      
       khmelnitsky :           {name : 'Хмельницький', nt : '{$cities['khmelnitsky']['night_t']}', dt : '{$cities['khmelnitsky']['day_t']}', sep : '...' , icon : '{$cities['khmelnitsky']['icon_chars']}'},
     },
    ug : {
        odessa :       {name : 'Одесса', nt : '{$cities['odessa']['night_t']}', dt : '{$cities['odessa']['day_t']}', sep : '...' , icon : '{$cities['odessa']['icon_chars']}'},
        herson :       {name : 'Херсон', nt : '{$cities['herson']['night_t']}', dt : '{$cities['herson']['day_t']}', sep : '...' , icon : '{$cities['herson']['icon_chars']}'},
        simferopol :    {name : 'Сімферополь', nt : '{$cities['simferopol']['night_t']}', dt : '{$cities['simferopol']['day_t']}', sep : '...' , icon : '{$cities['simferopol']['icon_chars']}'},
        zaporozhye : {name : 'Запоріжжя', nt : '{$cities['zaporozhye']['night_t']}', dt : '{$cities['zaporozhye']['day_t']}', sep : '...' , icon : '{$cities['zaporozhye']['icon_chars']}'},
        nikolaev :      {name : 'Миколаїв', nt : '{$cities['nikolaev']['night_t']}', dt : '{$cities['nikolaev']['day_t']}', sep : '...' , icon : '{$cities['nikolaev']['icon_chars']}'},
        yalta :           {name : 'Ялта', nt : '{$cities['yalta']['night_t']}', dt : '{$cities['yalta']['day_t']}', sep : '...' , icon : '{$cities['yalta']['icon_chars']}'},
     },
    vostok : {
        kharkiv :       {name : 'Харків', nt : '{$cities['kharkiv']['night_t']}', dt : '{$cities['kharkiv']['day_t']}', sep : '...' , icon : '{$cities['kharkiv']['icon_chars']}'},
        lugansk :      {name : 'Луганськ', nt : '{$cities['lugansk']['night_t']}', dt : '{$cities['lugansk']['day_t']}', sep : '...' , icon : '{$cities['lugansk']['icon_chars']}'},
        donetsk :      {name : 'Донецьк', nt : '{$cities['donetsk']['night_t']}', dt : '{$cities['donetsk']['day_t']}', sep : '...' , icon : '{$cities['donetsk']['icon_chars']}'},
    }, 
    sever : {
        chernihiv :     {name : 'Чернігів', nt : '{$cities['chernihiv']['night_t']}', dt : '{$cities['chernihiv']['day_t']}', sep : '...' , icon : '{$cities['chernihiv']['icon_chars']}'},
        sumi :           {name : 'Суми', nt : '{$cities['sumi']['night_t']}', dt : '{$cities['sumi']['day_t']}', sep : '...' , icon : '{$cities['sumi']['icon_chars']}'},
        zhitomir :      {name : 'Житомир', nt : '{$cities['zhitomir']['night_t']}', dt : '{$cities['zhitomir']['day_t']}', sep : '...' , icon : '{$cities['zhitomir']['icon_chars']}'},
    },
    kiev: {
        kiev : {name : 'Київ', nt : '{$cities['kiev']['night_t']}', dt : '{$cities['kiev']['day_t']}', sep : '...' , icon : '{$cities['kiev']['icon_chars']}'},
    },
};
// --------------------------
INFO;

$script .= <<<LOGIC

function resetIcon(ico_num) {
    var layer_length = app.project.item(ico_num).layers.length;
    for (var i = 1; i <= layer_length ; i++) {
        app.project.item(ico_num).layer(i).enabled = 0;
    }
}

// Устанавливаем тип погоды (иконку)
function setWeather(city, obl_ico, city_name) {
    if (city == null || city_name == null) {
        return ;
    }
    layer_num = getCityLayer(obl_ico, city.icon, city_name);
    app.project.item(obl_ico).layer(layer_num).enabled = 1;
     //$.writeln('Weather '+city.name+' ok');
}

function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}

function getCityLayer(obl_ico, icon, city_name){
    var i = 1;
    var layers_length = app.project.item(obl_ico).layers.length;
    var cityMustBe = city_name + '_' + icon;

    while (i <= layers_length) {
        var lay = myTrim(app.project.item(obl_ico).layer(i).name);

        if (lay == cityMustBe) {
            return i;
        }
        i++;
    }
    alert('Не найдены тип погоды ('+icon+') для города: ' + city_name);
}

function getTemperatureLayer(city, obl_item){
    var layers_length = app.project.item(obl_item).layers.length;
    var i = 1;
    while (i <= layers_length) {
        if (city.name == myTrim(app.project.item(obl_item).layer(i).name)) {
            return i+1;
        }
        i++;
    }
    var error_layer = app.project.item(obl_item).name;
    alert('Не найдено имя города: '+city.name+' (в слоях '+error_layer+')для установки температуры' );
}

function setTemp(city, obl_item) {
    if (city == null) {
        return ;
    }

    temperature_layer = getTemperatureLayer(city, obl_item)

    var city_temperature_text = app.project.item(obl_item).layer(temperature_layer).text.sourceText;
    var temperature = city.nt + city.sep + city.dt;
    city_temperature_text.setValue(temperature);
     $.writeln(city.name + ' : ' + temperature);
}

//======================================
var oblast_item = {};
var i = 1;
while (i <= app.project.items.length) {
    var comp1 = app.project.item(i);
    var str = myTrim(comp1.name);
        if (str.slice(-3) == 'obl') {
           var item = str.slice(0, -4);
           oblast_item[item] = i;
            $.writeln('['+i+']'+comp1.name+' = ' + oblast_item[item]);
        }
       i++;
}
$.writeln('--------------------------------');
var oblast_ico = {};
var i = 1;
while (i <= app.project.items.length) {
    var comp1 = app.project.item(i);
    var str = myTrim(comp1.name);
        if (str.slice(0, 3) == 'ico') {
            var city_ico = str.slice(4);
            oblast_ico[city_ico] = i;
            //$.writeln('['+i+']'+str+' = '  + oblast_ico[city_ico] );
        }
       i++;
}

// APPLY ALL SETTINGS
for (var oblast_name in oblasti) {
    $.writeln(oblasti);

    resetIcon(oblast_ico[oblast_name]);
    for (var city  in oblasti[oblast_name])
    {
        setWeather(oblasti[oblast_name][city], oblast_ico[oblast_name], city);
        //setWeather(oblasti[oblast_name][city], oblast_ico['kiev'], city);
        setTemp(oblasti[oblast_name][city], oblast_item[oblast_name]);
    }
}


LOGIC;

return $script;
}



$file = tempnam("js_tmp", "zip");
$zip = new ZipArchive();
$zip->open($file, ZipArchive::OVERWRITE);


// day == 1 is a current day

foreach ($days_cities as $day => $cities) {    
    $js_path = genPathByDay($day);
    $links[$day] = $js_path;
    // $zip->addFromString('file_name_within_archive.ext', $your_string_data);
    $zip->addFile(
        $js_path, 
        str_replace('.' . DIRECTORY_SEPARATOR .'js_files'. DIRECTORY_SEPARATOR , '', $js_path));
    file_put_contents($js_path, genJS($cities));    
}
$zip->close();
header('Content-Type: application/zip');
header('Content-Length: ' . filesize($file));
header('Content-Disposition: attachment; filename="Weather_'.$date.'_jsx.zip"');
readfile($file);
unlink($file); 


// Prepare File


// Stuff with content


// Close and send to users




// echo $content;

// foreach ($links as $key => $link) {
//     // file_force_download($link);
//     echo '<a href="'.$link.'"> days '.$key.'</a><br>';
// }

