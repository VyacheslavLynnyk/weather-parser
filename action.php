<?php
require_once __DIR__ . '/parsing.php';

//function getWeatherArray()
//{
//	$weatherRepository = new WeatherRepository();
//	return $weatherRepository->load();
//}

function printWeather(){
//	$dataArr = getWeatherArray();
    $weatherRepository = new WeatherRepository();
    $weather = $weatherRepository->load();

	//echo '<pre>';print_r($dataArr);echo '</pre>';
	
	// IF HAVE NO CACHE
	if (isset($weather) && $weather->getUpdateDate() == null) {
		echo '<h2 class="text-center">Нужно обновить данные</h2>';
		exit;
	}
    // Check for undefined icons
    $unknowIcons = $weather->getUnknownIcons();
	if (isset($unknowIcons) && is_array($unknowIcons) && sizeof($unknowIcons) > 0) {
		foreach ($unknowIcons as $icon => $data) {
			WeatherViewer::printReplacer($data);
		}
        exit;
	}
    $weather->fixIcons();

   ?>
	<div id="header-top">
		<div class="container">
			<table  class="table table-strip small-table">
				<tr>
					<th>Город</th>
                    <?php $days = $weather->getDays(); ?>
					<?php foreach ($days as $num => $day) : ?>
						<th><?= $day ?></th>	
					<?php endforeach; ?>				
				</tr>
			</table>
		</div>
	</div>
	<table class="table table-strip small-table">
	<tr>
		<th>Город</th>
		<?php foreach ($days as $num => $day) : ?>
			<th><?= $day ?></th>	
		<?php endforeach; ?>				
	</tr>

    <?php $weatherByCities = $weather->getCityDay(); ?>
	<?php foreach ($weatherByCities as $cityKey => $cityDays) : ?>
		<tr>
			<?php foreach ($cityDays as $dayNum => $city) : ?>
				<?php if ((int) $dayNum == 1) : ?>
					<td><?= $city['name'] ?></td>
				<?php endif; ?>		
				<td class="text-center">
					<img src="<?= $city['icon']; ?>" alt="">
					<p><?= $city['night_t'].'...'.$city['day_t'] ?></p>				
					<p><?= $city['desc']; ?></p>
				</td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
	
	</table>
	<?php
	echo "</pre>";

}



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
	$weatherRepository = new WeatherRepository();

	// Save weather to cache
	if ($weatherRepository->save($weather) !== true) : ?>
		<h3 class="alert-danger danger">
			Ошибка при сохранении временных файлов cache 
			(возможно закончилось место на диске или доступ к нему ограничен).
		</h3>'
	<?php endif;

	printWeather();
}
// Load from cache
if (isset($_GET['action']) && $_GET['action'] == 'load') {
	printWeather();
}
if (isset($_GET['action']) && $_GET['action'] == 'last_days') {
    $weatherRepository = new WeatherRepository();
    $weather = $weatherRepository->load();
//	$weatherArray = getWeatherArray();
	if (!is_object($weather)) {
		echo json_encode([
			'last_day' => 3,
			'update_date' => 'никогда' 
		]);			
	} else {
		echo json_encode([
//			'last_day' => count($weatherArray['days']),
			'last_day' => count($weather->getDays()),
//		 	'update_date' => $weatherArray['date']
		 	'update_date' => $weather->getUpdateDate()
		]);
	}
}
// Save new Icon assignation
if (isset($_POST['action']) && $_POST['action'] == 'save_icon') {
	$icon_type = trim(strip_tags($_POST['iconType']));
	$icon = trim(strip_tags($_POST['iconReplace']));

    $weatherRepository = new WeatherRepository();
    $weather = $weatherRepository->load();

	if ($weather->setIconConvert($icon_type, $icon)) {
        $weatherRepository->save($weather);
		echo 'saved';
		exit;
	}
	echo 'error';
    exit;
} 