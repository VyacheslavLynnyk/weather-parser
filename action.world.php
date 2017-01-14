<?php
// *************************************************
// ================ REQUESTS =======================
// *************************************************

// Update and write changes to cache
if (isset($_POST['action']) && $_POST['action'] == 'update') {
	$days = (isset($_POST['days'])) ? (int) $_POST['days'] : null;
	if ($days < 1) {
		echo 'Days Error';
		exit;
	}

	$cities = [
		//centr
		'london' => 'лондон',
		'brusel' => 'брюссель',
		'milan' => 'милан',
		'amsterdam' => 'амстердам',

		'new_york' => 'нью-йорк',
		'washgton' => 'вашингтон',
		'mexico' => 'мехико',
		'toronto' => 'торонто',

		'deli' => 'дели',
		'tokio' => 'токио',
		'pekin' => 'пекин',
		'seul' => 'сеул',

		'dubay' => 'дубай',
		'kair' => 'каир',
		'er_riyad' => 'рияд',
		'abudaby' => 'абу-даби',
	];

	$weather = new WeatherNight($cities);
	$weather->get($days); 

	// Save weather to cache
    $weatherRepository = new WeatherRepository();
	if ($weatherRepository->save($weather, 'world') !== true) : ?>
		<h3 class="alert-danger danger">
			Ошибка при сохранении временных файлов cache 
			(возможно закончилось место на диске или доступ к нему ограничен).
		</h3>'
	<?php endif;

	WeatherViewer::printWeather($weather);
}

// Load from cache
if (isset($_GET['action']) && $_GET['action'] == 'load') {
	$weather = WeatherRepository::load('world');
	$weather->fixIcons();
	WeatherViewer::printWeather($weather);
}

// Save options (Last update and days view)
if (isset($_GET['action']) && $_GET['action'] == 'last_days') {
    $weatherRepository = new WeatherRepository();
    $weather = $weatherRepository->load('world');
	if (!is_object($weather)) {
		echo json_encode([
			'last_day' => 3,
			'update_date' => 'никогда' 
		]);			
	} else {
		echo json_encode([
			'last_day' => count($weather->getDays()),
		 	'update_date' => $weather->getUpdateDate()
		]);
	}
}

// Save new Icon assignation
if (isset($_POST['action']) && $_POST['action'] == 'save_icon') {
	$icon_type = trim(strip_tags($_POST['iconType']));
	$icon = trim(strip_tags($_POST['iconReplace']));

    $weatherRepository = new WeatherRepository();
    $weather = $weatherRepository->load('world');

	if ($weather->setIconConvert($icon_type, $icon)) {
        $weatherRepository->save($weather, 'world');
		echo 'saved';
		exit;
	}
	echo 'error';
    exit;
} 