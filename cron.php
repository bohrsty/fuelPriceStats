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
if(file_exists('config.php') && is_readable('config.php')) {
    require_once('config.php');
} else {
    echo PHP_EOL.'config.php not exists or not readable!'.PHP_EOL;
    exit(1);
}

// load sources
require_once('src/php/Cron.php');

// get object and run job
$cron = new Cron($config);
$retVal = $cron->runJob();

// exit
exit($retVal);
