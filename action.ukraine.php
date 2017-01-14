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
		'vinnitsa' => 'Винница',
		'dnepropetrovsk' => 'днепр-303007131',
		'kirovograd' => 'кропивницкий',
		'poltava' => 'полтава',
		'cherkasy' => 'черкассы',
		// zapad
		'lviv' => 'львов',
		'rivne' => 'ровно',
		'ivano-frankivsk' => 'ивано-франковск',
		'chernivtsy' => 'черновцы',
		'lutsk' => 'луцк',
		'ternopol' => 'тернополь',
		'uzhgorod' => 'ужгород',
		'khmelnitsky' => 'хмельницкий',
		// ug
		'odessa' => 'одесса',
		'herson' => 'херсон',
		'simferopol' => 'симферополь',
		'zaporozhye' => 'запорожье',
		'nikolaev' => 'николаев',
		'yalta' => 'ялта',
		// vostok
		'kharkiv' => 'харьков',
		'lugansk' => 'луганск',
		'donetsk' => 'донецк',
		// server
		'chernihiv' => 'чернигов',
		'sumi' => 'сумы',
		'zhitomir' => 'житомир',
		'kiev' => 'киев'
	];

	$weather = new Weather($cities);
	$weather->get($days); 

	// Save weather to cache
    $weatherRepository = new WeatherRepository();
	if ($weatherRepository->save($weather, 'ukraine') !== true) : ?>
		<h3 class="alert-danger danger">
			Ошибка при сохранении временных файлов cache 
			(возможно закончилось место на диске или доступ к нему ограничен).
		</h3>'
	<?php endif;

	WeatherViewer::printWeather($weather);
}

// Load from cache
if (isset($_GET['action']) && $_GET['action'] == 'load') {
	$weather = WeatherRepository::load('ukraine');
	$weather->fixIcons();
	WeatherViewer::printWeather($weather);
}

// Save options (Last update and days view)
if (isset($_GET['action']) && $_GET['action'] == 'last_days') {
    $weatherRepository = new WeatherRepository();
    $weather = $weatherRepository->load('ukraine');
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
    $weather = $weatherRepository->load('ukraine');

	if ($weather->setIconConvert($icon_type, $icon)) {
        $weatherRepository->save($weather, 'ukraine');
		echo 'saved';
		exit;
	}
	echo 'error';
    exit;
} 