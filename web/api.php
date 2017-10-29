<?php
/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// load config (require_once() fills $config array)
if(file_exists('../config.php') && is_readable('../config.php')) {
    require_once('../config.php');
} else {
    $config = false;
}

// load sources
require_once('../src/php/FuelPriceStats.php');

// get object and handle request
$api = new FuelPriceStats($config);
// set header and return JSON
header('Content-type:application/json');
echo json_encode($api->handle());
