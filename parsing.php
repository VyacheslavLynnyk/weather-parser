<?php
// Create DOM from URL or file
require __DIR__. '/simplehtmldom_1_5/simple_html_dom.php';

date_default_timezone_set('Europe/Kiev');

mb_internal_encoding("UTF-8");
function mb_ucfirst($text) {
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

Abstract class WeatherA
{
	protected $weather;

	protected $updateDate;

	public function get(){}

	public function getCityDay($days){}

	public static function getByCity($city, $days = 3){}

	public function getUpdateDate(){}
}

class Weather extends WeatherA
{

	protected $cities = [];

	protected $weather = [];

	protected $weatherCityDay = [];

	protected $days = [];

	protected $updateDate;
	
	public function __construct(array $cities)
	{
		$this->setCities($cities);
	}

	public function setCities(array $cities)
	{
		foreach ($cities as $keyName => $cityName) {
			$this->cities = array_merge($this->cities, $cities);		
		}
	}
	public function getDays()
	{
		return $this->days;
	}

	public function getUpdateDate()
	{
		return $this->updateDate;
	}
	public function getCityDay($days = 3)
	{
		if (sizeof($this->weatherCityDay) < 1) {
			$this->get($days);
		}
		return $this->weatherCityDay;
	}

	public function get($days = 3)
	{
		if (isset($this->weather) && sizeof($this->weather) > 0) {
			//echo 'from cache';
			return $this->weather;
		}
		$i = 0;
		foreach ($this->cities as $keyName => $cityName) {
			$cityData = self::getByCity($cityName, $days);
			++$i;
			foreach ($cityData as $day => $data) {
				if (!is_numeric($day)) {
					continue;
				}								 	
				$cityName = preg_replace('/\-\d+/', '', $cityName);	
				$cityName = mb_ucfirst($cityName);	

				$icon_type = (string) trim($data['icon_type']);
				if (in_array($icon_type, ['d000', 'n000'])) {
					$icon_char = 's';
				} elseif (in_array($icon_type, ['d300', 'd200', 'd100', 'n200', 'd311'])) {
					$icon_char = 'sc';
				} elseif (in_array($icon_type, ['d400', 'n600', 'n300', 'n400','n100', 'd310'])) {
					$icon_char = 'c';
				} elseif (in_array($icon_type, ['d210', 'n410','d220', 'd420', 'd410', 'd430'])) {
					$icon_char = 'cr';
				} elseif (in_array($icon_type, ['d400', 'n430', 'd330', 'd320'])) {
					$icon_char = 'rf';
				} elseif (in_array($icon_type,
					['d312', 'd331', 'd432',
                        'n422', 'n412','d421',
                        'd412', 'd322', 'd312',
                        'd212', 'd422' , 'd411',
                        'd222', 'd221', 'd321',
                    ])) {
					    $icon_char = 'cn';
				} else {
					$icon_char = 'xz';
					echo '<p class="alert-danger danger">Неизвестный значек погоды в городе '
						. $cityName .' за '.$data['date'].' <br>'
                        . $icon_type . ' - ' . $data['desc'] .
						 '</p><br>';
				//	exit;
				}

				if ($icon_char == null) {
					// print_r((string) trim($data['icon_type']));
					// echo '<br>';
					// var_dump($$icon_type);
					// exit;
					$icon_char = 'xz2';
				}								

				$this->weather[$day][$keyName] = $data;
				$this->weather[$day][$keyName]['icon_chars'] = $icon_char;
				$this->weather[$day][$keyName]['name'] =  $cityName;

				$this->weatherCityDay[$keyName][$day] = $data;
				$this->weatherCityDay[$keyName][$day]['icon_chars'] = $icon_char;
				$this->weatherCityDay[$keyName][$day]['name'] = $cityName;
				if ($i == 1) {
					$this->days[$day] = substr($data['date'],0 , -5);
				}
			}			
		}
		$this->updateDate = date('Y-m-d H:i:s');
		return $this->weather;
	}

	public static function getByCity($city, $days = 3)
	{	
		$cityData['name'] = mb_strtolower($city, 'UTF-8');
		
		//print_r($cityData['name']);
		
		$html = file_get_html('https://sinoptik.ua/погода-'.$cityData['name']);

		// print_r($html);
		// Get date; min, max temperature
		$weatherFull = $html->find('div[id=blockDays]', 0)->plaintext;
		
		//print_r($weatherFull);

		$weatherArr = explode('&nbsp', $weatherFull);
		unset($weatherArr[0]);
		unset($weatherArr[8]);
		foreach ($weatherArr as $key => $weather) {
			if (!is_numeric($key) ||(int) $key > $days) {
				break;
			}
			$pattern = "/\;\s+(\W+\d+\s\W+)['мин.'\s](\+\d+|\-\d+|\d+)|['макс.'\s](\+\d+|\-\d+|\d+)/U";
			
			$data = preg_match_all($pattern, $weather, $matches);
			// print_r($matches);

			
			$cityData[$key]['date'] = trim(rtrim($matches[1][0], '   мин.')). date(' Y');
			$cityData[$key]['night_t'] = trim($matches[2][0]);
			$cityData[$key]['day_t'] = trim($matches[3][1]);			
		}		
		
		foreach ($cityData as $day => $data) {
			if (!is_numeric($day)) {
				continue;
			}
			$cityData[$day]['icon'] = $html->find('.weatherIco', $day)->children(0)->getAttribute('src');

			$cityData[$day]['desc'] = ($html->find('.weatherIco', $day)->getAttribute('title'));

			$type = $html->find('.weatherIco', $day)->getAttribute('class');
			$cityData[$day]['icon_type'] = ltrim($type, 'weatherIco');		
		}

		return  $cityData;
	}

}

class WeatherViewer {

	public function printWeather($day)
	{
		echo '<img src="'.$img.'">';
		echo $desc;
		echo $type;
	
	}

	public function showOrder($order)
	{
		
	}
}

class WeatherRepository {

	protected $dataSavePath =  'parse_tmp';

	public function __construct($date = null)
	{
		if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . $this->dataSavePath)) {
			if (!mkdir($this->dataSavePath)) {
				throw new Exception("Can't create " . $this->dataSavePath." folder", 1);
			}
		}

 	}

 	// date('Y-m-d')
	public function load($date = null)
	{
		if (!isset($date)) {
			$date = 'last';
		}
		$loadPath = '.'. DIRECTORY_SEPARATOR . $this->dataSavePath . DIRECTORY_SEPARATOR . $date .'_tmp.ini';
		if (!is_file($loadPath))
		{
			return false;
		}
		$loadedCache = file_get_contents($loadPath);
		return unserialize($loadedCache);

	}

	public function save(WeatherA $weather, $dateBool = null)
	{	
		if (!$weather->getUpdateDate()) {
			throw new Exception("Update date isn't set", 1);
			
		}
		$updateDateTime = $weather->getUpdateDate();
		$updateDate = substr($updateDateTime, 0, 10);

		$data['date'] =  $updateDateTime;		
		$data['weatherByDate']= $weather->get();	
		$data['weatherByCity']= $weather->getCityDay();	
		$data['days'] = $weather->getDays();		
		$dataEncoded = serialize($data);
		// TODO: FIX FOR ALL OS
		if (!isset($dateBool)) {
			$updateDate = 'last';
		}
		$savePath = $this->dataSavePath . DIRECTORY_SEPARATOR . $updateDate .'_tmp.ini';

		// Check on rewrite data
		if (is_file($savePath)) {			
			if ($this->rewritePrompt() == false) {
				return false;
			}
			//var_dump('rewrited');
		}

		//var_dump($savePath);
		file_put_contents(			
			$savePath,
			$dataEncoded
		);

		return (is_file($savePath)) ? true : flase;		
	}

	public function rewritePrompt()
	{
		return true;
	}
}
