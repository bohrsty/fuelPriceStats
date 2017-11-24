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

//layout
import {layout, newDisplay} from './layout';

/*
 * function to get data from internal api
 */
var getData = (timeString, callback) => {
	
	// calculate from and to dates
	var to = new Date();
	var from = new Date(to.getTime());

	switch(timeString) {
		
		case 'all':
			from = new Date('2017-10-01');
			break;
		
		case 'month':
			from = new Date(from.setMonth(from.getMonth() - 1));
			break;
        
		case 'week':
            from = new Date(from.setDate(from.getDate() - 7));
            break;
	}
	
	// prepare uri
	var uri = 'api.php?from=' + encodeURIComponent(dateFormat(from)) + '&to=' + encodeURIComponent(dateFormat(to));
	
	// call internal api
	$.get(uri, callback);
}

/*
 * function to format date in yyyy-mm-dd
 */
var dateFormat = (date) => {
	
    // get month and day
    var day = '' + date.getDate();
    if(day.length < 2) {
        day = '0' + day;
    }
    var month = '' + (date.getMonth() + 1);
    if(month.length < 2) {
        month = '0' + month;
    }
    
	// return formatted date string
	return date.getFullYear() + '-' + month + '-' + day;
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
module.exports = {
	getData: getData,
	layout: layout,
	newDisplay: newDisplay,
	urlParam: urlParam
}
