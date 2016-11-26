<?php
// Create DOM from URL or file
require __DIR__ . '/simplehtmldom_1_5/simple_html_dom.php';

date_default_timezone_set('Europe/Kiev');

mb_internal_encoding("UTF-8");
function mb_ucfirst($text)
{
    return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
}

Abstract class WeatherA
{
    protected $weather;

    protected $updateDate;

    public function get()
    {
    }

    public function getCityDay($days)
    {
    }

    public static function getByCity($city, $days = 3)
    {
    }

    public function getUpdateDate()
    {
    }
}

class Weather extends WeatherA
{

    protected $cities = [];

    protected $weather = [];

    protected $weatherCityDay = [];

    protected $days = [];

    protected $updateDate;

    protected $unknownIcons = [];

    const SETTINGS_PATH = 'settings.txt';

    public function __construct(array $cities = [])
    {
        if (isset($cities) && sizeof($cities) > 0) {
            $this->setCities($cities);
        }
    }

    public function setCities(array $cities)
    {
        foreach ($cities as $keyName => $cityName) {
            $this->cities = array_merge($this->cities, $cities);
        }
    }

    public function getCities()
    {
        return $this->cities;
    }

    public function getDays()
    {
        return $this->days;
    }

    public function setDays(array $days)
    {
        $this->days = $days;
    }

    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    public function setUpdateDate($date)
    {
        $this->updateDate = $date;
    }

    public function getCityDay($days = 3)
    {
        if (sizeof($this->weatherCityDay) < 1) {
            $this->get($days);
        }
        return $this->weatherCityDay;
    }

    public function setCityDay(array $weatherCityDay)
    {
        $this->weatherCityDay = $weatherCityDay;
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

                if (!isset($data['icon_type']) || !isset($data['icon'])) {
                    throw new Exception('Parse error, check icon');
                }
                // Convert icon from site to AE Project
                $icon_char = $this->convertIcon($data['icon'], $data['icon_type'], $data['desc']);

                if ($icon_char == null) {
                    // print_r((string) trim($data['icon_type']));
                    // echo '<br>';
                    // var_dump($$icon_type);
                    // exit;
                    $icon_char = 'xz2';
                }

                $this->weather[$day][$keyName] = $data;
                $this->weather[$day][$keyName]['icon_chars'] = $icon_char;
                $this->weather[$day][$keyName]['name'] = $cityName;

                $this->weatherCityDay[$keyName][$day] = $data;
                $this->weatherCityDay[$keyName][$day]['icon_chars'] = $icon_char;
                $this->weatherCityDay[$keyName][$day]['name'] = $cityName;
                if ($i == 1) {
                    $this->days[$day] = substr($data['date'], 0, -5);
                }
            }
        }
        $this->updateDate = date('Y-m-d H:i:s');
        return $this->weather;
    }

    public function set(array $weather)
    {
        $this->weather = $weather;
    }

    public function fixIcons()
    {
        foreach ($this->weatherCityDay as $keyName => $daysData) {
            foreach ($daysData as $day) {
                if (!is_numeric($day)) {
                    continue;
                }
                if ($this->weather[$day][$keyName]['icon_chars'] == 'xz'
                    || $this->weather[$day][$keyName]['icon_chars'] == 'xz2'
                ) {
                    echo $this->weather[$day][$keyName]['icon_type'];
                    exit;
                    $newIconChar = $this->convertIcon(
                        $this->weather[$day][$keyName]['icon'],
                        $this->weather[$day][$keyName]['icon_type'],
                        $this->weather[$day][$keyName]['desc']
                    );

                    if ($newIconChar != 'xz') {
                        $this->weather[$day][$keyName]['icon_chars'] = $newIconChar;
                        $this->weatherCityDay[$keyName][$day]['icon_chars'] = $newIconChar;
                    }
                }
            }
        }
        $weatherRepository = new WeatherRepository();
        $weatherRepository->save($this);
    }

    public function convertIcon($icon, $icon_type, $desc)
    {
//        $data['icon'], $data['icon_type'], $data['desc']
        $icon_type = (string)trim($icon_type);
        $weatherIcons = json_decode(file_get_contents(self::SETTINGS_PATH), 1);

        foreach ($weatherIcons as $type => $icons) {
            if (in_array($icon_type, $icons)) {
                return $type;
            }
        }
        $this->unknownIcons[$icon_type] = [
            'count' => (int)($this->unknownIcons[$icon_type]['count'] ?? 0) + 1,
            'icon' => $icon,
            'icon_type' => $icon_type,
            'desc' => $desc
        ];

        return 'xz';
    }

    public function getUnknownIcons()
    {
        return $this->unknownIcons;
    }

    public function setUnknowIcons(array $unknowIcons)
    {
        $this->unknownIcons = $unknowIcons;
    }

    public function setIconConvert($icon_type, $icon)
    {
        if (isset($icon) && isset($icon_type)) {
            $weatherIcons = json_decode(file_get_contents(self::SETTINGS_PATH), 1);
            $weatherIcons[$icon][] = $icon_type;
            $weatherIcons[$icon] = array_unique($weatherIcons[$icon]);
            file_put_contents(self::SETTINGS_PATH, json_encode($weatherIcons, 1));
            unset($this->unknownIcons[$icon_type]);
            return true;
        }
        throw new Exception('Bad parameters for icon_type or icon');
    }

    public static function getByCity($city, $days = 3)
    {
        $cityData['name'] = mb_strtolower($city, 'UTF-8');

        //print_r($cityData['name']);

        $html = file_get_html('https://sinoptik.ua/погода-' . $cityData['name']);

        // print_r($html);
        // Get date; min, max temperature
        $weatherFull = $html->find('div[id=blockDays]', 0)->plaintext;

        //print_r($weatherFull);

        $weatherArr = explode('&nbsp', $weatherFull);
        unset($weatherArr[0]);
        unset($weatherArr[8]);
        foreach ($weatherArr as $key => $weather) {
            if (!is_numeric($key) || (int)$key > $days) {
                break;
            }
            $pattern = "/\;\s+(\W+\d+\s\W+)['мин.'\s](\+\d+|\-\d+|\d+)|['макс.'\s](\+\d+|\-\d+|\d+)/U";

            $data = preg_match_all($pattern, $weather, $matches);
            // print_r($matches);


            $cityData[$key]['date'] = trim(rtrim($matches[1][0], '   мин.')) . date(' Y');
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

        return $cityData;
    }

}

class WeatherViewer
{
    const ICONS_PATH = 'imgs/icons/';

    public static function printReplacer(array $data)
    {
        ?>
        <div class="replacer thumbnail alert-warning text-center ">
            <div class="row">
                <h4>Выберите отображение для этой иконки</h4>
                <div class="col-sm-3">
                    <img src="<?= $data['icon']; ?>" alt="<?= $data['icon_type']; ?>">
                    <p><?= $data['desc'] ?></p>
                </div>
                <div class="col-sm-9 select-image" data-icon="<?= $data['icon_type']; ?>">
                    <img class="thumbnail pull-left" src="<?= self::ICONS_PATH . 's.png' ?>" alt="s">
                    <img class="thumbnail pull-left" src="<?= self::ICONS_PATH . 'sc.png' ?>" alt="c">
                    <img class="thumbnail pull-left" src="<?= self::ICONS_PATH . 'c.png' ?>" alt="sc">
                    <img class="thumbnail pull-left" src="<?= self::ICONS_PATH . 'cr.png' ?>" alt="cr">
                    <img class="thumbnail pull-left" src="<?= self::ICONS_PATH . 'rf.png' ?>" alt="rf">
                    <img class="thumbnail pull-left" src="<?= self::ICONS_PATH . 'cn.png' ?>" alt="cn">
                </div>
            </div>
        </div>
        <?php
    }

    public function printWeather($day)
    {
//        echo '<img src="' . $img . '">';
//        echo $desc;
//        echo $type;

    }

    public function showOrder($order)
    {

    }
}

class WeatherRepository
{

    const DATA_SAVE_PATH = 'parse_tmp';

    public function __construct($date = null)
    {
        if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . self::DATA_SAVE_PATH)) {
            if (!mkdir(self::DATA_SAVE_PATH)) {
                throw new Exception("Can't create " . self::DATA_SAVE_PATH . " folder", 1);
            }
        }

    }

    // date('Y-m-d')
    public static function load($date = null)
    {
        if (!isset($date)) {
            $date = 'last';
        }
        $loadPath = '.' . DIRECTORY_SEPARATOR . self::DATA_SAVE_PATH . DIRECTORY_SEPARATOR . $date . '_tmp.ini';
        if (!is_file($loadPath)) {
            return false;
        }
        $loadedCache = file_get_contents($loadPath);
        $data = unserialize($loadedCache);
        $weather = new Weather();
        $weather->setUpdateDate($data['date']);
        $weather->set($data['weatherByDate']);
        $weather->setCityDay($data['weatherByCity']);
        $weather->setDays($data['days']);
        $weather->setUnknowIcons($data['unknownIcons']);
        if (isset($data['cities']) && is_array($data['cities']) && sizeof($data['cities']) > 0) {
            $weather->setCities($data['cities']);
        }
        $weather->setCityDay($data['weatherByCity']);
        return $weather;

    }

    public function save(WeatherA $weather, $dateBool = null)
    {
        if (!$weather->getUpdateDate()) {
            throw new Exception("Update date isn't set", 1);

        }
        $updateDateTime = $weather->getUpdateDate();
        $updateDate = substr($updateDateTime, 0, 10);

        $data['date'] = $updateDateTime;
        $data['weatherByDate'] = $weather->get();
        $data['weatherByCity'] = $weather->getCityDay();
        $data['days'] = $weather->getDays();
        $data['unknownIcons'] = $weather->getUnknownIcons();
        $data['cities'] = $weather->getCities();
        $dataEncoded = serialize($data);
        // TODO: FIX FOR ALL OS
        if (!isset($dateBool)) {
            $updateDate = 'last';
        }
        $savePath = self::DATA_SAVE_PATH . DIRECTORY_SEPARATOR . $updateDate . '_tmp.ini';

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

        return (is_file($savePath)) ? true : false;
    }

    public function rewritePrompt()
    {
        return true;
    }
}
