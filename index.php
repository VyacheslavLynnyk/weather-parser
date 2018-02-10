<?php session_start();

if (!isset($_COOKIE["password"]) or sha1($_COOKIE["password"]) !== '453407e93d72014d648ec95503217423ca7d13b0') {
	header('LOCATION: /auth.php');
	exit;
}
if (isset($_GET['do']) && strtolower($_GET['do']) == 'on') {
	if (is_file('lock_1.cfg')) {
		rename('lock_1.cfg', 'lock_0.cfg');
	} else {
	    touch('lock_0.cfg');
    }
    die('weather is turned "ON". Go to <a href="/">Weather site</a>');
} elseif (isset($_GET['do']) && strtolower($_GET['do']) == 'off') {
	if (is_file('lock_0.cfg')) {
		rename('lock_0.cfg', 'lock_1.cfg');
	} else {
		touch('lock_1.cfg');
	}
	die('weather is turned "OFF". Go to <a href="/">Weather site</a>');
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
    <link rel="stylesheet" href="./styles/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="./styles/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="./styles/html5shiv.min.js"></script>
    <script src="./styles/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="./styles/main.css">
</head>
<body>
<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <div class="weather-logo"></div>
            </a>

            <span>
                <label style="margin-top: 15px" class="pull-left" for="weather-place">Режим:</label>
                <select class="nav form-control pull-left" name="in" id="weather-place"
                        style="width: 100px; height: 24px; padding: 0 2px;
                            margin-left: 5px; margin-right: 15px; margin-top: 15px">
                    <option value="ukraine" <?= (isset($_COOKIE['load_in']) && $_COOKIE['load_in'] == 'ukraine') ? ' selected' : '' ?>>Украина</option>
                    <option value="world" <?= (isset($_COOKIE['load_in']) && $_COOKIE['load_in'] == 'world') ? ' selected' : '' ?>>Мир</option>
                </select>
            </span>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <div class="nav navbar-nav margin-left5px padding-top15">
                <label class="pull-left" for="weather-days">Дней:</label>
                <select class="nav form-control pull-left" name="days" id="weather-days">
					<?php for ($i = 1; $i <= 10; $i++) : ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
					<?php endfor ?>
                </select>

            </div>
            <ul class="nav navbar-nav">
                <!-- <li class="active"><a href="./index.php">Home</a></li>     -->
                <li><a href="#" id="weather-update">
                        <div class="update-time"><img src="imgs/update-tIme64x64-arrows.png" alt=""></div>
                    </a>
                </li>
            </ul>
            <div class="nav navbar-nav text-center" style="padding-left: 20px; padding-top: 15px">
                <span><strong id="last-update"></strong><br><sup>Последнее обновление</sup></span>
            </div>
            <ul class="nav navbar-nav navbar-right">

                <!-- <li><a href="#" id="download-txt">Скачать TXT<span class="sr-only">(current)</span></a></li> -->
                <li class="download-script">
                    <a href="./save-js.php" class="btn-download" id="download-js">Скачать jsx</a>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container-fluid">
    <div class="loading" style="display: none;">
        <img src="imgs/loading.gif" alt="загрузка">
    </div>
    <div class="weather-result"></div>

</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="./scripts/jquery-1.12.4.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./scripts/bootstrap.min.js"></script>
<script src="./scripts/main.js"></script>
</body>
</html>