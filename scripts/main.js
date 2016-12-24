/**
 * Created by litter on 03.12.16.
 */
$(document).ready(function () {

var weather_result = $('.weather-result');
var loading = $('.loading');
var days = $('#weather-days');
var date_update = $('#last-update');
var place = $('#weather-place');

// $('#loading-image').bind('ajaxStart', function(){
//     $(this).show();
// }).bind('ajaxStop', function(){
//     $(this).hide();
// });

function loadWeather() {
    // Get weather on load
    weather_result.hide();
    $.get('/action.php',
        {'action': 'load', 'in' : place.val()},
        function (response) {
            weather_result.html(response);
            weather_result.fadeIn(500); 
        }
    );
}

function updateOptions() {
    $.get(
        '/action.php',
        {'action': 'last_days', 'in' : place.val()},
        function (response) {
            console.log(response);
            var data = JSON.parse(response);
            console.log(data);
            days.val(data.last_day);
            date_update.html(data.update_date);

        }
    );
}

$('#weather-update').on('click', function (e) {
    e.preventDefault();

    // Get current date in navigation
    var now = new Date();
    var nowDate = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + ('0' + now.getDate()).slice(-2);
    console.log(date_update.text().slice(0, 10));
    console.log(nowDate);
    if (date_update.text().slice(0, 10) === nowDate) {
        if (!confirm('Вы действительно хотите обновить данные? (Возможно дизайнер уже скачал скрипты со старыми данными)')) {
            return;
        }
    }

    weather_result.hide();
    loading.fadeIn(500);

    $.post('/action.php',
        {'action': 'update', 'days': days.val(), 'in' : place.val()},
        function (response) {
            loading.hide();
            weather_result.html(response);
            weather_result.fadeIn(500);
            setTimeout(updateOptions, 2000);
        }
    );
});


// Icon FIXER
$('body').on('click', '.replacer .select-image > img', function (e) {
    e.preventDefault();
    var iconReplace = $( this ).attr('alt');
    var iconType = $( this ).parent().data('icon');
    $.post('/action.php',
        {
            'action': 'save_icon',
            'iconType' : iconType,
            'iconReplace' : iconReplace,
            'in' : place.val()
        },
        function (response) {
            console.log(response);
            if (response == 'saved') {
                loadWeather();
            } else {
                weather_result.html('Ошибка при сохранении');
            }

        }
    );
});


// Document ready was here

    place.on('change', function(){
        updateOptions();
        loadWeather();
    });


    // LOAD WEATHER
    loadWeather();

    // Get last number of days on load
    updateOptions();

    // SHOW/HIDE TABLE HEAD ON SCROLL UP/DOWN
    var showHead = 0;
    $(window).scroll(function () {
        var table_header = $('#header-top');
        var minWidth = 320;
        var pageWidth = $(window).width();

        if (days.val() >= 7 ) {
            minWidth = 950;
        } else if (days.val() == 6 ) {
            minWidth = 820;
        } else if (days.val() == 5 ) {
            minWidth = 690;
        } else if (days.val() == 4 ) {
            minWidth = 560;
        }

        if (window.scrollY > 111 && pageWidth >= minWidth) {
            showHead++;
        } else {
            showHead = 0;
        }
        if (showHead == 1) {
            table_header.fadeIn(500);
        }
        if (showHead == 0) {
            table_header.fadeOut(500);
        }

    });


});


