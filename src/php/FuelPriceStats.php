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
            $data[$station->getName()][$station->getFuel()][] = array(
                'runtime' => $station->getRuntime('Y-m-d H:i:s'),
                'price' => $station->getPrice(),
            );
        }
        
        // successfull
        return array(
            'status' => 'OK',
            'message' => 'Request successfull',
            'data' => $data,
        );
    }
}
