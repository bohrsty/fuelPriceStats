<?php
require_once('ApiResponse.php');
require_once('Station.php');

/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ApiResponse {
    
    /**
     * @var array $config
     */
    private $config;
    /**
     * @var string $status
     */
    private $status;
    /**
     * @var string $message
     */
    private $message;
    /**
     * @var array $stationData
     */
    private $stationData;
    /**
     * @var DateTimeInterface $runtime
     */
    private $runtime;
    
    /*
     * getter
     */
    public function getStatus() {
        return $this->status;
    }
    public function getMessage() {
        return $this->message;
    }
    public function getStationData() {
        return $this->stationData;
    }
    public function getRuntime() {
        return $this->runtime;
    }
    
    
    /*
     * constructor
     */
    public function __construct($config, $apiResponse) {
        
        // set config
        $this->config = $config;
        
        // set values
        $this->status = ($apiResponse['ok'] === true ? 'OK' : 'ERROR');
        $this->message = ($apiResponse['ok'] === true ? '' : $apiResponse['message']);
        $this->runtime = $apiResponse['runtime'];
        $this->stationData = ($apiResponse['ok'] === true ? $this->parseStationData($apiResponse['prices']) : array());
    }
    
    
    /*
     * methods
     */
    /**
     * parseStationData() parses the station data and returns it as array
     *
     * @param array the raw data from api response
     * @return array parsed station data as array of Station objects
     */
    private function parseStationData($stationData) {
        
        // prepare return
        $return = array();
        
        // walk through station data
        foreach($stationData as $uuid => $data) {
            
            // check open
            if($data['status'] == 'open') {
                
                // create Station
                $return[] = new Station(array(
                    'uuid' => $uuid,
                    'name' => $this->config['stations'][$uuid],
                    'fuel' => $this->config['fuel'],
                    'price' => $data[$this->config['fuel']],
                    'runtime' => $this->runtime,
                ));
            } else {
                
                // create Station with empty price
                $return[] = new Station(array(
                    'uuid' => $uuid,
                    'name' => $this->config['stations'][$uuid],
                    'fuel' => $this->config['fuel'],
                    'price' => null,
                    'runtime' => $this->runtime,
                ));
            }
        }
        
        // return array
        return $return;
    }
}