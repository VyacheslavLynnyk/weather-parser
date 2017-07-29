<?php
// Generate AfterEffect script file
function genJS($cities, $dateUpdate)
{
    $script = <<<"INFO"
// ---- INFO ----- DATE-UPDATE: {$dateUpdate};

// settings
var cities = {
	'london' :	{icoName: 'london', name : 'лондон', cont : 'europe' , day_t : '{$cities['london']['day_t']}', night_t : '{$cities['london']['night_t']}', day_w : '{$cities['london']['icon_chars']}', night_w : '{$cities['london']['icon_chars2']}'},
	
	'brusel' :	{icoName: 'brusel', name : 'брюсель', cont : 'europe' , day_t : '{$cities['brusel']['day_t']}', night_t : '{$cities['brusel']['night_t']}', day_w : '{$cities['brusel']['icon_chars']}', night_w : '{$cities['brusel']['icon_chars2']}'},
	
	'milan' :	{icoName: 'milan', name : 'мілан', cont : 'europe' , day_t : '{$cities['milan']['day_t']}', night_t : '{$cities['milan']['night_t']}', day_w : '{$cities['milan']['icon_chars']}', night_w : '{$cities['milan']['icon_chars2']}'},
	
	'amsterdam' :	{icoName: 'amsterdam', name : 'амстердам', cont : 'europe' , day_t : '{$cities['amsterdam']['day_t']}', night_t : '{$cities['amsterdam']['night_t']}', day_w : '{$cities['amsterdam']['icon_chars']}', night_w : '{$cities['amsterdam']['icon_chars2']}'},
	

	
	'new_york' :	{icoName: 'new_york', name : 'нью-йорк', cont : 'america' , day_t : '{$cities['new_york']['day_t']}', night_t : '{$cities['new_york']['night_t']}', day_w : '{$cities['new_york']['icon_chars']}', night_w : '{$cities['new_york']['icon_chars2']}'},
	
	'washgton' :	{icoName: 'washgton', name : 'вашингтон', cont : 'america' , day_t : '{$cities['washgton']['day_t']}', night_t : '{$cities['washgton']['night_t']}', day_w : '{$cities['washgton']['icon_chars']}', night_w : '{$cities['washgton']['icon_chars2']}'},
	
	'mexico' :	{icoName: 'mexico', name : 'мехіко', cont : 'america' , day_t : '{$cities['mexico']['day_t']}', night_t : '{$cities['mexico']['night_t']}', day_w : '{$cities['mexico']['icon_chars']}', night_w : '{$cities['mexico']['icon_chars2']}'},
	
	'toronto' :	{icoName: 'toronto', name : 'торонто', cont : 'america' , day_t : '{$cities['toronto']['day_t']}', night_t : '{$cities['toronto']['night_t']}', day_w : '{$cities['toronto']['icon_chars']}', night_w : '{$cities['toronto']['icon_chars2']}'},
	
	
	
	'deli' :	{icoName: 'deli', name : 'делі', cont : 'azia' , day_t : '{$cities['deli']['day_t']}', night_t : '{$cities['deli']['night_t']}', day_w : '{$cities['deli']['icon_chars']}', night_w : '{$cities['deli']['icon_chars2']}'},
	
	'tokio' :	{icoName: 'tokio', name : 'токіо', cont : 'azia' , day_t : '{$cities['tokio']['day_t']}', night_t : '{$cities['tokio']['night_t']}', day_w : '{$cities['tokio']['icon_chars']}', night_w : '{$cities['tokio']['icon_chars2']}'},
	
	'pekin' :	{icoName: 'pekin', name : 'пекін', cont : 'azia' , day_t : '{$cities['pekin']['day_t']}', night_t : '{$cities['pekin']['night_t']}', day_w : '{$cities['pekin']['icon_chars']}', night_w : '{$cities['pekin']['icon_chars2']}'},
	
	'seul' :	{icoName: 'seul', name : 'сеул', cont : 'azia' , day_t : '{$cities['seul']['day_t']}', night_t : '{$cities['seul']['night_t']}', day_w : '{$cities['seul']['icon_chars']}', night_w : '{$cities['seul']['icon_chars2']}'},
	
	
	
	'dubay' :	{icoName: 'dubay', name : 'дубаї', cont : 'africa' , day_t : '{$cities['dubay']['day_t']}', night_t : '{$cities['dubay']['night_t']}', day_w : '{$cities['dubay']['icon_chars']}', night_w : '{$cities['dubay']['icon_chars2']}'},
	
	'kair' :	{icoName: 'kair', name : 'каїр', cont : 'africa' , day_t : '{$cities['kair']['day_t']}', night_t : '{$cities['kair']['night_t']}', day_w : '{$cities['kair']['icon_chars']}', night_w : '{$cities['kair']['icon_chars2']}'},
	
	'er_riyad' :	{icoName: 'er_riyad', name : 'ер-ріяд', cont : 'africa' , day_t : '{$cities['er_riyad']['day_t']}', night_t : '{$cities['er_riyad']['night_t']}', day_w : '{$cities['er_riyad']['icon_chars']}', night_w : '{$cities['er_riyad']['icon_chars2']}'},
	
	'abudaby' :	{icoName: 'abudaby', name : 'абу-дабі', cont : 'africa' , day_t : '{$cities['abudaby']['day_t']}', night_t : '{$cities['abudaby']['night_t']}', day_w : '{$cities['abudaby']['icon_chars']}', night_w : '{$cities['abudaby']['icon_chars2']}'},
	
	
};
// -------------------
INFO;
        
$script .= <<<LOGIC
      
// functions
function myTrim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}

function resetIcons(ico) {	
	for (var ico_num in ico) {
		var layer_length = app.project.item(ico[ico_num]).layers.length;		
		for (var i = 1; i <= layer_length ; i++) {
			app.project.item(ico[ico_num]).layer(i).enabled = 0;
			// $.writeln( '== ' + ico_num + ' : ' + ico[ico_num]);
		}
    }
}

function setCityTemperature( city, contTemp ){
	if (city.name == '' || city.cont == '' ) {
		alert('Обнаружены пустые параметры: city:' + city.name + ', cont:' + city.cont + ', dt:' + city.day_t + ', nt:' + city.night_t );
        return ;    
    }

    var numItem = contTemp[ city['cont'] + '_temperatur' ];
	var layers_length = app.project.item(numItem).layers.length;
    var i = 1;
	var temperature_layer = 0;
	while (i <= layers_length) {
	
        var city_name = city.name;
        city_name = city_name.toLowerCase();
        var layer_text = myTrim(app.project.item(numItem).layer(i).name);
        layer_text = layer_text.toLowerCase();
        
	    if (city_name == layer_text) {
			 temperature_layer = i + 1;
			 break;
		}   
		i++;
	}
	if ( temperature_layer <= 0 ) {
		alert ('Не обнаружен город: ' + city.name);
	}
    var city_temperature_text = app.project.item(numItem).layer(temperature_layer).text.sourceText;		
	var temperature = city['night_t'] + '    ' + city['day_t'];
	// var temperature = '+0' + city.sep + '+0';
	city_temperature_text.setValue(temperature);
    $.writeln(city.name + ' : ' + temperature);    
}

function setCityWeather( city, ico ){
	var dw = ico['ico_day_' + city['icoName']];
	var nw = ico['ico_night_' + city['icoName']];
	var weathers = {};
	weathers[dw] = city.day_w;
	weathers[nw] = city.night_w;

	for (var weather in weathers) {
		$.writeln( weather );
		weather = +weather;
		var layer_length = app.project.item(weather).layers.length;	
		var weatherFound = 0;
		for (var i = 1; i <= layer_length ; i++) {
				var foundWeather = app.project.item(weather).layer(i).name;
				foundWeather = foundWeather.toLowerCase();
				if (foundWeather == weathers[weather]) {
						weatherFound++;
						app.project.item(weather).layer(i).enabled = 1;
						break;
				}
				
				// $.writeln( '== ' + ico_num + ' : ' + ico[ico_num]);
		}
		if (weatherFound < 1) {
			alert(' Не найдена погода ( ' + weathers[weather] + ' ) для city:' + city.name + ', cont:' + city.cont + ', dt:' + city.day_t + ', nt:' + city.night_t );
		}
	}
	
}

// --------------------------------
// ico - масив со всеми слоями иконок
var ico = [];
var contTemp = [];
var i = 1;
while (i <= app.project.items.length) {
    var layerBlock = app.project.item(i);
	var layerName = myTrim(layerBlock.name);
    if (layerName.slice(0, 6) == 'block_') {
        $.writeln('+ ' + layerName);          
    }
   if (layerName.slice(0, 4) == 'ico_') {
		ico[layerName] = i;
        //$.writeln( '++ ' + layerName);          
    }
    if (layerName.slice(-11) == '_temperatur') {
		contTemp[layerName] = i;
        //$.writeln( '** ' + layerName);          
    }
   // $.writeln(layerBlock.name); 
    i++;
}

// ACTION ***

resetIcons(ico);

for (var city in cities) {
	setCityTemperature( cities[city], contTemp);	
	setCityWeather( cities[city], ico);
}
LOGIC;

    return $script;
}
