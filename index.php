<?php if (!isset($_COOKIE["password"]) or sha1($_COOKIE["password"]) !== '453407e93d72014d648ec95503217423ca7d13b0') {
  header('LOCATION: /auth.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Weather</title>

    <!-- Bootstrap -->
   <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <link rel="stylesheet" href="main.css">
  </head>
  <body>
  <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Погода</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">   
        <div class="nav navbar-nav">
          <label for="weather-days">за количество дней:</label>
          <select class="nav"  name="days" id="weather-days" style="margin-top: 15px; margin-right: 15px">
            		<option value="1">1</option>
            		<option value="2">2</option>
            		<option value="3">3</option>
            		<option value="4">4</option>
            		<option value="5">5</option>
            		<option value="6">6</option>
            		<option value="7">7</option>
            </select>

           </div>                  
            <ul class="nav navbar-nav">
            <!-- <li class="active"><a href="./index.php">Home</a></li>     -->
            	<li class="active"><a href="#" id="weather-update">Обновить</a></li>                     
          	</ul>
            <div class="nav navbar-nav text-center" style="padding-left: 20px; padding-top: 15px">
             <span >Последнее обновление: <strong id="last-update"></strong></span>
           </div>
          <ul class="nav navbar-nav navbar-right">
           
            <!-- <li><a href="#" id="download-txt">Скачать TXT<span class="sr-only">(current)</span></a></li> -->
            <li><a href="./save-js.php" id="download-js">Скачать JS</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
    <div class="container">    
      <div class="loading" style="display: none;">
        <img src="imgs/loading.gif" alt="загрузка">
      </div>
    	<div class="weather-result"></div>

    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script>
    
      var weather_result = $('.weather-result');
      var loading = $('.loading');
      var days = $('#weather-days');
      var date_update = $('#last-update');
      
    // $('#loading-image').bind('ajaxStart', function(){
    //     $(this).show();
    // }).bind('ajaxStop', function(){
    //     $(this).hide();
    // });
       function updateOptions() {            
            $.get(
              './action.php',
              {'action' : 'last_days'},
              function(response) {
                console.log(response);
                var data = JSON.parse(response);
                console.log(data);
                days.val(data.last_day);
                date_update.html(data.update_date);

              } 
            );          
          }

      $('#weather-update').on('click', function(e){        
        e.preventDefault();

        // Get current date
        var now = new Date();
        var nowDate = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + (now.getDate());
        if (date_update.text().slice(0,10) === nowDate) {
          if (confirm('Вы действительно хотите обновить данные? (Возможно дизайнер уже скачал скрипты со старыми данными)')) {
            
          } else {
             return
          }
        }
       
        weather_result.hide();
        loading.fadeIn(500);

        $.post('./action.php',
          {'action' : 'update', 'days' : days.val()},
          function(response) {
            loading.hide();
            weather_result.html(response);
            weather_result.fadeIn(500);
            setTimeout(updateOptions , 2000);            
          } 
        );
      });

      $( document ).ready(function(){
          

          // Get weather on load            
          $.get('./action.php',
              {'action' : 'load'},
              function(response) {
                weather_result.html(response);
              } 
          );      
          // Get last number of days on load
          updateOptions();

        // SHOW/HIDE TABLE HEAD ON SCROLL UP/DOWN
          var showHead = 0;
          $( window ).scroll(function() {
            var table_header = $('#header-top');
            
            if (window.scrollY > 111) {
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
          
  </script>
  </body>
</html>