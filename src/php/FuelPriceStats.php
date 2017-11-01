<?php
require_once('DatabaseConnection.php');

/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class FuelPriceStats {
    
    /**
     * @var array $config
     */
    private $config;
    
    
    /*
     * constructor
     */
    public function __construct($config) {
        $this->config = $config;
    }
    
    
    /*
     * methods
     */
    /**
     * handle() handles the incomming api call and returns the JSON reply
     *
     * @return string the JSON return
     */
    public function handle() {
        
        // prepare return
        $data = array();
        
        // check config
        if($this->config === false) {
            return array(
                'status' => 'ERROR',
                'message' => 'config.php not exists or not readable!',
                'data' => array(),
            );
        } else {
            $config = self::checkConfig($this->config);
            if($config['result'] == 'ERROR') {
                return array(
                    'status' => 'ERROR',
                    'message' => 'config not valid: '.PHP_EOL.implode(PHP_EOL, $config['messages']),
                    'data' => array(),
                );
            } else {
                $this->config = $config['config'];
            }
        }
        
        // check from and to
        $from = '';
        $to = new DateTimeImmutable();;
        if(isset($_GET['from']) && preg_match('/\d\d\d\d\-\d\d\-\d\d/', $_GET['from']) === 1) {
            $from = new DateTimeImmutable($_GET['from'].' 00:00:00');
        } else {
            return array(
                'status' => 'ERROR',
                'message' => 'URL parameter "from" not set or wrong format (yyyy-mm-dd)',
                'data' => array(),
            );
        }
        if(isset($_GET['to'])) {
            if(preg_match('/\d\d\d\d\-\d\d\-\d\d/', $_GET['to']) === 1) {
                $from = new DateTimeImmutable($_GET['from'].' 00:00:00');
            } else {
                return array(
                    'status' => 'ERROR',
                    'message' => 'URL parameter "to" wrong format (yyyy-mm-dd)',
                    'data' => array(),
                );
            }
        }
        
        // get data from database
        $stations = array();
        try {
            $db = new DatabaseConnection($this->config);
            $stations = $db->loadStationsToArray($from, $to);
        } catch(Exception $e) {
            return array(
                'status' => 'ERROR',
                'message' => 'Error connecting database: "'.$e->getMessage().'"',
                'data' => array(),
            );
        }
        
        // extract data
        foreach($stations as $station) {
            $price = $station->getPrice();
            if($price == 'NULL') {
                if(!isset($data[$station->getName()][$station->getFuel()])) {
                    $price = 0;
                } else {
                    $lastStation = end($data[$station->getName()][$station->getFuel()]);
                    $price = $lastStation['price'];
                }
            }
            $data[$station->getName()][$station->getFuel()][] = array(
                'runtime' => $station->getRuntime('Y-m-d H:i:s'),
                'price' => $price,
            );
        }
        
        // successfull
        return array(
            'status' => 'OK',
            'message' => 'Request successfull',
            'data' => $data,
        );
    }
    
    
    /**
     * checkConfig($config) check if the config is usable
     * 
     * @param array $config the config to check
     * @return array an array containing the check result and error messages
     */
    public static function checkConfig($config) {
        
        // check if config is array
        if(!is_array($config)) {
            return array(
                'result' => 'ERROR',
                'messages' => array(
                    'config has to be an array',
                ),
                'config' => array(),
            );
        }
        
        // check values
        $result = 'OK';
        $messages = array();
        // apikey
        if(!isset($config['apikey']) || $config['apikey'] == '') {
            $result = 'ERROR';
            $messages[] = 'apikey: is required and must not be an empty string';
        } else {
            $messages[] = 'apikey: OK';
        }
        // baseurl
        if(isset($config['baseurl']) && $config['baseurl'] == '') {
            $config['baseurl'] = 'https://creativecommons.tankerkoenig.de/json/prices.php';
        }
        if(!isset($config['baseurl']) || $config['baseurl'] == '' || filter_var($config['baseurl'], FILTER_VALIDATE_URL) === false) {
            $result = 'ERROR';
            $messages[] = 'baseurl: is required, must not be an empty string and has to be a valid url';
        } else {
            $messages[] = 'baseurl: OK';
        }
        // stations
        if(!isset($config['stations']) || count($config['stations']) < 1) {
            $result = 'ERROR';
            $messages[] = 'stations: is required and has to contain at least one element';
        } else {
            $messages[] = 'stations: OK';
        }
        // fuel
        if(!isset($config['fuel']) || $config['fuel'] == '') {
            $result = 'ERROR';
            $messages[] = 'fuel: is required and must not be an empty string';
        } else {
            $messages[] = 'fuel: OK';
        }
        // databaseFile
        if(isset($config['databaseFile']) && $config['databaseFile'] == '') {
            $config['databaseFile'] = 'data/fuelPriceStats.db';
        }
        if(!isset($config['databaseFile']) || $config['databaseFile'] == '') {
            $result = 'ERROR';
            $messages[] = 'databaseFile: is required and must not be an empty string';
        } else {
            $messages[] = 'databaseFile: OK';
        }
        // delayRandom
        if(!isset($config['delayRandom']) || !is_bool($config['delayRandom'])) {
            $result = 'ERROR';
            $messages[] = 'delayRandom: is required and has to be boolean';
        } else {
            $messages[] = 'delayRandom: OK';
        }
        
        // return
        return array(
            'result' => $result,
            'messages' => $messages,
            'config' => $config,
        );
    }
}
