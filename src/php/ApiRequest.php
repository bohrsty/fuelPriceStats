<?php
require_once('ApiResponse.php');

/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ApiRequest {
    
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
     * get() organizes the api call and returns the returned data
     * 
     * @return ApiResponse the response object containing the data
     */
    public function get() {
        
        // prepare station uuids
        $stations = array();
        foreach($this->config['stations'] as $uuid => $name) {
            $stations[] = $uuid;
        }
        
        // contact api
        $apiResult = $this->callApi(array(
            'baseurl' => $this->config['baseurl'],
            'apikey' => $this->config['apikey'],
            'stations' => $stations,
        ));
        
        // prepare response
        $response = new ApiResponse($this->config, $apiResult);
        
        // return
        return $response;
    }
    
    
    /**
     * callApi() fires the api call and returns the returned data
     *
     * @param array the required information for the call (key, stations, url)
     * @return array the response of the api call
     */
    private function callApi($config) {
        
        // runtime
        $runtime = new DateTime();
        
        // call
        $response = file_get_contents($config['baseurl'].'?ids='.implode(',', $config['stations']).'&apikey='.$config['apikey']);
        
        // check response
        if($response === false) {
            return array(
                'ok' => false,
                'message' => 'API call failed',
                'runtime' => $runtime,
            );
        }
        
        // prepare response
        $response = json_decode($response, true);
        
        // check json
        if(is_null($response)) {
            return array(
                'ok' => false,
                'message' => 'Parsing JSON failed ['.json_last_error_msg().']',
                'runtime' => $runtime,
            );
        }
        
        // add runtime
        $response['runtime'] = $runtime;
        
        // return
        return $response;
    }
}