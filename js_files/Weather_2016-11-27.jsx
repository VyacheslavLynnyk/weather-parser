// ---- INFO ----- DATE-UPDATE: 2016-11-26 16:50;
var oblasti = {
    centr : {
        vinnitsa :  {name : 'Вінниця', nt : '0', dt : '+5', sep : '...' , icon : 'sc'},
        dnepropetrovsk :  {name : 'Дніпро', nt : '-1', dt : '+6', sep : '...' , icon : 'rf'},
        kirovograd :         {name : 'Кропивницький', nt : '-2', dt : '+4', sep : '...' , icon : 'cr'},
        poltava :              {name : 'Полтава', nt : '-1', dt : '+3', sep : '...' , icon : 'cn'},
        cherkasy :           {name : 'Черкаси', nt : '0', dt : '+4', sep : '...' , icon : 'cr'},
    },
    zapad : {
       lviv :                       {name : 'Львів', nt : '+1', dt : '+6', sep : '...' , icon : 'cn'},
       'ivano-frankivsk' :    {name : 'Івано- Франківськ', nt : '+2', dt : '+6', sep : '...' , icon : 'cn'},
       rivne :                 {name : 'Рівне', nt : '+1', dt : '+5', sep : '...' , icon : 'cn'},
       chernivtsy :             {name : 'Чернівці', nt : '+3', dt : '+6', sep : '...' , icon : 'sc'},       
       lutsk :                     {name : 'Луцьк', nt : '+2', dt : '+6', sep : '...' , icon : 'cn'},
       ternopol :                {name : 'Тернопіль', nt : '+1', dt : '+6', sep : '...' , icon : 'cn'},
       uzhgorod :              {name : 'Ужгород', nt : '+1', dt : '+5', sep : '...' , icon : 'sc'},      
       khmelnitsky :           {name : 'Хмельницький', nt : '+1', dt : '+7', sep : '...' , icon : 'cn'},
     },
    ug : {
        odessa :       {name : 'Одесса', nt : '+4', dt : '+8', sep : '...' , icon : 's'},
        herson :       {name : 'Херсон', nt : '+2', dt : '+1', sep : '...' , icon : 'c'},
        simferopol :    {name : 'Сімферополь', nt : '+1', dt : '+1', sep : '...' , icon : 'cr'},
        zaporozhye : {name : 'Запоріжжя', nt : '-1', dt : '+7', sep : '...' , icon : 'cr'},
        nikolaev :      {name : 'Миколаїв', nt : '+3', dt : '+9', sep : '...' , icon : 'sc'},
        yalta :           {name : 'Ялта', nt : '+6', dt : '+1', sep : '...' , icon : 'rf'},
     },
    vostok : {
        kharkiv :       {name : 'Харків', nt : '-1', dt : '+4', sep : '...' , icon : 'cn'},
        lugansk :      {name : 'Луганськ', nt : '-3', dt : '+6', sep : '...' , icon : 'cr'},
        donetsk :      {name : 'Донецьк', nt : '-3', dt : '+6', sep : '...' , icon : 'rf'},
    }, 
    sever : {
        chernihiv :     {name : 'Чернігів', nt : '+1', dt : '+5', sep : '...' , icon : 'sc'},
        sumi :           {name : 'Суми', nt : '-1', dt : '+4', sep : '...' , icon : 'cn'},
        zhitomir :      {name : 'Житомир', nt : '+2', dt : '+8', sep : '...' , icon : 'cn'},
    },
    kiev: {
        kiev : {name : 'Київ', nt : '+2', dt : '+8', sep : '...' , icon : 'cn'},
    },
};
// --------------------------
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

