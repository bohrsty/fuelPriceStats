<?php
/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// api key for tankerkoenig.de api
$config['apikey'] = '';

// base url to get prices from api
$config['baseurl'] = '';

// stations to the prices for
$config['stations'] = array(
//    'uuid' => 'display name',
);

// fuel type
$config['fuel'] = '';

// path to database file (default: ./data/fuelPriceStats.db
$config['databaseFile'] = '';

// delay tankerkoenig.de api call for random time to fulfill their terms of use
$config['delayRandom'] = true;
