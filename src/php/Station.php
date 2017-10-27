<?php

/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Station {
    
    /**
     * @var string $uuid
     */
    private $uuid;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $fuel
     */
    private $fuel;
    /**
     * @var float $price
     */
    private $price;
    /**
     * @var DateTimeInterface $runtime
     */
    private $runtime;
    
    /*
     * getter
     */
    public function getUuid() {
        return $this->uuid;
    }
    public function getName() {
        return $this->name;
    }
    public function getFuel() {
        return $this->fuel;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getRuntime($format = '') {
        
        // check format
        if($format == '') {
            return $this->runtime;
        } else {
            return $this->runtime->format($format);
        }
    }
    
    
    /*
     * constructor
     */
    public function __construct($stationData) {
        
        // set values
        $this->uuid = $stationData['uuid'];
        $this->name = $stationData['name'];
        $this->fuel = $stationData['fuel'];
        $this->price = $stationData['price'];
        $this->runtime = $stationData['runtime'];
    }
    
    
    /*
     * methods
     */
}