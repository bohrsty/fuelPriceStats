<?php
/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// api key for tankerkoenig.de api (required: true, default: '')
$config['apikey'] = '';

// base url to get prices from api (required: true, default: 'https://creativecommons.tankerkoenig.de/json/prices.php')
$config['baseurl'] = '';

// stations to the prices for (required: true, default: array())
$config['stations'] = array(
//    'uuid' => 'display name',
);

// fuel type (required: true, default: '')
$config['fuel'] = '';

// path to database file  (required: true, default: data/fuelPriceStats.db)
$config['databaseFile'] = '';

// delay tankerkoenig.de api call for random time to fulfill their terms of use (required: true, default: true)
$config['delayRandom'] = true;
