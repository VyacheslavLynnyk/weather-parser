<?php
require_once __DIR__ . '/parsing.php';

function getWeatherArray()
{
	$weatherRepository = new WeatherRepository();
	return $weatherRepository->load();
}

function printWeather(){
	$dataArr = getWeatherArray();
	//echo '<pre>';print_r($dataArr);echo '</pre>';
	
	// IF HAVE NO CACHE
	if (empty($dataArr) || !is_array($dataArr)) {
		echo '<h2 class="text-center">Нужно обновить данные</h2>';
		exit;
	}

	?> 
	<div id="header-top">
		<div class="container">
			<table  class="table table-strip small-table">
				<tr>
					<th>Город</th>
					<?php foreach ($dataArr['days'] as $num => $day) : ?>
						<th><?= $day ?></th>	
					<?php endforeach; ?>				
				</tr>
			</table>
		</div>
	</div>
	<table class="table table-strip small-table">
	<tr>
		<th>Город</th>
		<?php foreach ($dataArr['days'] as $num => $day) : ?>
			<th><?= $day ?></th>	
		<?php endforeach; ?>				
	</tr>
	
	<?php foreach ($dataArr['weatherByCity'] as $cityKey => $cityDays) : ?>
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
if ($_POST['action'] == 'update') {
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
if ($_GET['action'] == 'load') {
	printWeather();
}
if ($_GET['action'] == 'last_days') {
	$weatherArray = getWeatherArray();
	if (!is_array($weatherArray)) {
		echo json_encode([
			'last_day' => 3,
			'update_date' => 'никогда' 
		]);			
	} else {
		echo json_encode([
			'last_day' => count($weatherArray['days']),
		 	'update_date' => $weatherArray['date'] 
		]);	
	}
}