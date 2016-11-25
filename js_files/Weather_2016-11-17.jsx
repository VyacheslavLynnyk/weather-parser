// ---- INFO ----- DATE-UPDATE: 2016-11-17 16:06;
var oblasti = {
    centr : {
        vinnitsa :  {name : 'Вінниця', dt : '-5', nt : '0', sep : '...' , icon : 'cr'},
        dnepropetrovsk :  {name : 'Дніпро', dt : '+1', nt : '+10', sep : '...' , icon : 'cr'},
        kirovograd :         {name : 'Кропивницький', dt : '+5', nt : '+9', sep : '...' , icon : 'cr'},
        poltava :              {name : 'Полтава', dt : '+4', nt : '+6', sep : '...' , icon : 'cr'},
        cherkasy :           {name : 'Черкаси', dt : '+3', nt : '+8', sep : '...' , icon : 'c'},
    },
    zapad : {
       lviv :                       {name : 'Львів', dt : '+7', nt : '+10', sep : '...', icon : 'sc' },
       'ivano-frankivsk' :    {name : 'Івано- Франківськ', dt : '+6', nt : '+11', sep : '...' , icon : 'c'},
       rivne :                     {name : 'Рівне', dt : '+7', nt : '+12', sep : '...' , icon : 'c'},
       chernivtsy :             {name : 'Чернівці', dt : '+7', nt : '+13', sep : '...' , icon : 'sc'},       
       lutsk :                     {name : 'Луцьк', dt : '+6', nt : '+12', sep : '...' , icon : 'c'},
       ternopol :                {name : 'Тернопіль', dt : '+7', nt : '+12', sep : '...' , icon : 'c'},
       uzhgorod :              {name : 'Ужгород', dt : '+9', nt : '+13', sep : '...' , icon : 'sc'},       
       khmelnitsky :           {name : 'Хмельницький', dt : '+4', nt : '+7', sep : '...' , icon : 'cr'},
     },
    ug : {
        odessa :       {name : 'Одесса', dt : '+7', nt : '+11', sep : '...' , icon : 'sc'},
        herson :       {name : 'Херсон', dt : '+6', nt : '+11', sep : '...' , icon : 'sc'},
        simferopol :    {name : 'Сімферополь', dt : '+7', nt : '+14', sep : '...' , icon : 'cr'},
        zaporozhye : {name : 'Запоріжжя', dt : '+5', nt : '+11', sep : '...' , icon : 'sc'},
        nikolaev :      {name : 'Миколаїв', dt : '+5', nt : '+11', sep : '...' , icon : 'sc'},
        yalta :           {name : 'Ялта', dt : '+9', nt : '+14', sep : '...' , icon : 'sc'},
     },
    vostok : {
        kharkiv :       {name : 'Харків', dt : '+4', nt : '+5', sep : '...' , icon : 'sc'},
        lugansk :      {name : 'Луганськ', dt : '+3', nt : '+8', sep : '...' , icon : 'cr'},
        donetsk :      {name : 'Донецьк', dt : '+4', nt : '+8', sep : '...' , icon : 'sc'},
    }, 
    sever : {
        chernihiv :     {name : 'Чернігів', dt : '+3', nt : '+6', sep : '...', icon : 'c' },
        sumi :           {name : 'Суми', dt : '+2', nt : '+5', sep : '...', icon : 'c' },
        zhitomir :      {name : 'Житомир', dt : '+4', nt : '+8', sep : '...', icon : 'c' },     
    },
    kiev: {
        kiev : {name : 'Київ', dt : '+4', nt : '+8', sep : '...', icon : 'c'}
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
    var temperature = city.dt + city.sep + city.nt;
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
    $.writeln(oblast);

    resetIcon(oblast_ico[oblast_name]);
    for (var city  in oblasti[oblast_name])
    {
        setWeather(oblasti[oblast_name][city], oblast_ico[oblast_name], city);
        //setWeather(oblasti[oblast_name][city], oblast_ico['kiev'], city);
        setTemp(oblasti[oblast_name][city], oblast_item[oblast_name]);
    }
}

