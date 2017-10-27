<?php
require_once('ApiRequest.php');
require_once('DatabaseConnection.php');

/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Cron {
    
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
     * runJob() initiates the api call and saves the response in the database
     * 
     * @return int the return value to be returned from to cron
     */
    public function runJob() {
        
        // get data from api
        $request = new ApiRequest($this->config);
        $response = $request->get();
        
        // check response
        if($response->getStatus() != 'OK') {
            echo PHP_EOL.$response->getMessage().PHP_EOL;
            return 1;
        }
        
        // save in database
        try {
            $db = new DatabaseConnection($this->config);
        } catch(Exception $e) {
            echo PHP_EOL.$e->getMessage().PHP_EOL;
            return 1;
        }
        $dbResult = $db->saveStationsFromArray($response->getStationData());
        
        // check result
        if($dbResult['status'] != 'OK') {
            echo PHP_EOL.$dbResult['message'].PHP_EOL;
            return 1;
        }
        
        // successful
        return 0;
    }
}
