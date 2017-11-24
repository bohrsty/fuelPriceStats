/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

"use strict";

//import required modules
import $ from 'jquery';

/*
 * translation data
 */
var data = {
    de: {
        'document.title': 'Benzinpreise',
        'period.all': 'Alle',
        'period.lastMonth': 'letzter Monat',
        'period.lastWeek': 'letzte Woche',
        'period.last24h': 'letzte 24h',
        'fuel.e5': 'Benzin E5',
        'fuel.e10': 'Benzin E10',
        'fuel.diesel': 'Diesel',
        'display.on': 'am',
        'lang.de': 'Deutsch',
        'lang.en': 'Englisch'
    },
    en: {
        'document.title': 'Fuel prices',
        'period.all': 'All',
        'period.lastMonth': 'last month',
        'period.lastWeek': 'last week',
        'period.last24h': 'last 24h',
        'fuel.e5': 'Gasoline E5',
        'fuel.e10': 'Gasoline E10',
        'fuel.diesel': 'Diesel',
        'display.on': 'on',
        'lang.de': 'German',
        'lang.en': 'English'
    }
};

/*
 * translate function
 */
var t = (tString) => {
    
    // get language
    var l = (urlParam('l') == null ? 'de' : urlParam('l'));
    
    // translate
    if(!Object.keys(data[l]).length) {
        return tString;
    }
    if(Object.keys(data[l][tString]).length) {
        return data[l][tString];
    } else {
        return tString;
    }
}

/*
 * get an named url param
 */
var urlParam = (name) => {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if(results==null){
       return null;
    }
    else{
       return decodeURI(results[1]) || null;
    }
}

//export object
module.exports = t;