/*
 * This file is part of the fuelPriceStats package.
 *
 * (c) Nils Bohrs
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

"use strict";

// import required modules
import $ from 'jquery';
import t from './translate';

/*
 * global layout
 */
// prepare layout
var layout = $('<div>');

// add grid class
layout.addClass('container')

// prepare panel
var panel = $('<div>')
	.addClass('panel-body')
	.attr('id', 'mainPanel')
	.appendTo($('<div>')
		.addClass('panel')
		.appendTo(layout)
	);

// add button row
var buttonRow = $('<div>')
    .addClass('row')
    .appendTo(panel);

// add time switch
var timeSwitch = $('<div>')
    .addClass('col-lg-5')
    .addClass('col-sm-12')
    .append(
        $('<div>')
            .addClass('btn-group')
            .addClass('btn-group-sm')
            .addClass('text-center')
            .addClass('time-buttons')
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .attr('data-period', 'all')
                    .attr('data-type', 'time')
                    .text(t('period.all'))
            )
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .attr('data-period', 'month')
                    .attr('data-type', 'time')
                    .text(t('period.lastMonth'))
            )
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .addClass('active')
                    .attr('data-period', 'week')
                    .attr('data-type', 'time')
                    .text(t('period.lastWeek'))
            )
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .attr('data-period', 'day')
                    .attr('data-type', 'time')
                    .text(t('period.last24h'))
            )
    )
    .appendTo(buttonRow);

// add fuel switch
var fuelSwitch = $('<div>')
    .addClass('col-lg-5')
    .addClass('col-sm-12')
    .append(
        $('<div>')
            .addClass('btn-group')
            .addClass('btn-group-sm')
            .addClass('text-center')
            .addClass('fuel-buttons')
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .addClass('active')
                    .attr('data-fuel', 'e5')
                    .attr('data-type', 'fuel')
                    .text(t('fuel.e5'))
            )
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .attr('data-fuel', 'e10')
                    .attr('data-type', 'fuel')
                    .attr('disabled', true)
                    .text(t('fuel.e10'))
            )
            .append(
                $('<button>')
                    .addClass('btn')
                    .addClass('btn-default')
                    .attr('data-fuel', 'diesel')
                    .attr('data-type', 'fuel')
                    .attr('disabled', true)
                    .text(t('fuel.diesel'))
            )
    )
    .appendTo(buttonRow);

// add language switch
var langSwitch = $('<div>')
 .addClass('col-lg-2')
 .addClass('col-sm-12')
 .addClass('pull-right')
 .append(
     $('<div>')
         .addClass('btn-group')
         .addClass('btn-group-sm')
         .addClass('text-center')
         .addClass('lang-buttons')
         .append(
             $('<button>')
                 .addClass('btn')
                 .addClass('btn-default')
                 .attr('data-type', 'lang')
                 .attr('data-lang', 'de')
                 .text(t('lang.de'))
         )
         .append(
             $('<button>')
                 .addClass('btn')
                 .addClass('btn-default')
                 .attr('data-type', 'lang')
                 .attr('data-lang', 'en')
                 .text(t('lang.en'))
         )
 )
 .appendTo(buttonRow);

/*
 * function to add new metrics-grapics display
 */
var newDisplay = (config) => {
	
	// prepare display
	var display = $('<div>')
        .addClass('col-lg-12')
        .append(
            $('<h4>')
            .text(config.station)
        )
        .append(
            $('<div>')
                .addClass('mg-display')
                .attr('id', config.id)
                .append(
                    $('<span>')
                        .addClass('last-price')
                        .addClass('text-bold')
                        .text(config.lastPrice)
                )
                .append(' ' + t('display.on') + ' ')
                .append(
                    $('<span>')
                        .addClass('last-runtime')
                        .addClass('text-bold')
                        .text(config.lastRuntime)
                )
        );
	
	// return
	return display;
};

// export layout
module.exports = {
	layout: layout,
	newDisplay: newDisplay
}
