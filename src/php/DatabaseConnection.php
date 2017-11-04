<?php
require_once('Station.php');

/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class DatabaseConnection {
    
    /**
     * @var array $config
     */
    private $config;
    /**
     * @var object $db
     */
    private $db;
    
    
    /*
     * constructor
     */
    public function __construct($config) {
        
        // set config
        $this->config = $config;
        
        // open connection
        $this->connect();
    }
    
    
    /*
     * methods
     */
    /**
     * connect() checks the file, schema, opens the connection and saves it in $this->db
     * 
     * @return void
     */
    private function connect() {
        
        // check if file given in config
        if(!isset($this->config['databaseFile']) || $this->config['databaseFile'] == '') {
            $this->config['databaseFile'] = 'data/fuelPriceStats.db';
        }
        // set absolute path
        $this->config['databaseFile'] = dirname(__FILE__).'/../../'.$this->config['databaseFile'];
        
        // check if database exists
        $createSchema = false;
        if(!file_exists($this->config['databaseFile'])) {
            $createSchema = true;
        }
        
        // open connection
        $db = new SQLite3($this->config['databaseFile']);
        
        // prepare schema sql
        $schemaSql = '
            CREATE TABLE fuelPriceStats (uuid VARCHAR(36), name VARCHAR(75), fuel VARCHAR(5), price DECIMAL(5,3), runtime DATETIME)
        ';
        // prepare index sql
        $indexSql = '
            CREATE INDEX IF NOT EXISTS dateindex ON fuelPriceStats (runtime)
        ';
        if($createSchema === true) {
            
            // execute
            $resSchema = $db->exec($schemaSql);
            $resIndex = $db->exec($indexSql);
            if($resSchema === false || $resIndex === false) {
                throw new Exception('Unable to create schema');
            }
        } else {
            
            // check if table exists
            $tableExists = $db->querySingle('SELECT COUNT(*) FROM (SELECT name FROM sqlite_master WHERE type="table" AND tbl_name="fuelPriceStats")') == 1;
            if($tableExists === false) {
                
                // execute
                $resSchema = $db->exec($schemaSql);
                $resIndex = $db->exec($indexSql);
                if($resSchema === false || $resIndex === false) {
                    throw new Exception('Unable to create schema');
                }
            }
        }
        
        // set $this->db
        $this->db = $db;
    }
    
    
    /**
     * saveStationsFromArray() save the station array data in the database
     *
     * @return array an array containing the status information
     */
    public function saveStationsFromArray($stationData) {
        
        // walk through stations
        foreach($stationData as $station) {
            
            // prepare insert
            $stmt = $this->db->prepare('
                INSERT INTO fuelPriceStats (uuid, name, fuel, price, runtime)
                VALUES (:uuid, :name, :fuel, :price, :runtime)
            ');
            // bind values
            $stmt->bindValue(':uuid', $station->getUuid());
            $stmt->bindValue(':name', $station->getName());
            $stmt->bindValue(':fuel', $station->getFuel());
            $stmt->bindValue(':price', (is_null($station->getPrice()) ? 'NULL' : $station->getPrice()));
            $stmt->bindValue(':runtime', $station->getRuntime('Y-m-d H:i:s'));
            // execute
            $result = $stmt->execute();
            
            // check insert
            if($result === false) {
                return array(
                    'status' => 'ERROR',
                    'message' => 'Failed to insert station data ['.$this->db->lastErrorMsg().']',
                );
            }
            
            // close statement
            $stmt->close();
        }
        
        // return
        return array(
            'status' => 'OK',
            'message' => 'successful',
        );
    }
    
    
    /**
     * loadStationsToArray($from, $to) load the station data from database between
     * $from and $to and returns it as array of Station
     *
     * @param DateTimeInterface $from the date and time to start the data from
     * @param DateTimeInterface $to the date and time to end the data
     * @return array an array of the resulting Station data
     */
    public function loadStationsToArray($from, $to) {
        
        // prepare return
        $stations = array();
            
        // prepare select
        $stmt = $this->db->prepare('
            SELECT *
            FROM fuelPriceStats 
            WHERE runtime BETWEEN :from AND :to
        ');
        // bind values
        $stmt->bindValue(':from', $from->format('Y-m-d H:i:s'));
        $stmt->bindValue(':to', $to->format('Y-m-d H:i:s'));
        // execute
        $result = $stmt->execute();
        
        // check insert
        if($result === false) {
            throw new Exception('Unable to get data from database ['.$this->db->lastErrorMsg().']');
        }
        
        // fetch data
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $row['runtime'] = new DateTime($row['runtime']);
            $stations[] = new Station($row);
        }
        
        // close statement
        $stmt->close();
        
        // return
        return $stations;
    }
}