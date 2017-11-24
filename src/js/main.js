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
import MG from 'metrics-graphics';
import d3 from 'd3';
import 'bootstrap';
import fPS from './fuelPriceStats';
import t from './translate';
// css files
import 'bootstrap/dist/css/bootstrap.css';
import 'metrics-graphics/dist/metricsgraphics.css';
import '../css/main.css';

// run if dom is ready
$(document).ready(() => {
    
    // add layout
    $('#content')
        .append(fPS.layout);
    
    // add active language
    var l = (fPS.urlParam('l') == null ? 'de' : fPS.urlParam('l'));
    $('.lang-buttons button[data-lang="' + l + '"]').addClass('active');
    
    // translate title
    $('title').text(t('document.title'));
    
    // get fuel and time values
    var fuel = $('button[data-type="fuel"].active').data('fuel');
    var time = $('button[data-type="time"].active').data('period');
    
    // get data from internal api
    fPS.getData(time, (data) => {
        
        // walk through data
        if(data.data.length == 0) {
            $('.mg-display').hide();
        } else {
            $('.mg-display').show();
        }
        for(var station in data.data) {
            
            // get fuel data
            var fuelData = data.data[station][fuel].map((entry) => {
                return {
                    runtime: new Date(entry.runtime),
                    price: entry.price
                };
            });
            
            // add display
            var lRt = fuelData[fuelData.length - 1].runtime;
            var id = station.replace(/[ \.\-\/äöüÄÖÜß]/g, '');
            $('#mainPanel')
                .append(
                    fPS.newDisplay({
                        id: id,
                        station: station,
                        lastRuntime: lRt.getDate() + '.' + (lRt.getMonth()+1) + '.' + lRt.getFullYear() + ' ' + lRt.getHours() + ':' + lRt.getMinutes(),
                        lastPrice: fuelData[fuelData.length - 1].price
                    })
                );
            
            // add metrics graphics
            MG.data_graphic({
                full_width: true,
                height: 400,
                target: '#' + id,
                x_accessor: 'runtime',
                y_accessor: 'price',
                min_y_from_data: true,
                data: fuelData,
                european_clock: true,
                y_rollover_format: (data) => {
                    return '';
                },
                x_rollover_format: (data) => {
                    var runtime = data.runtime;
                    var price = data.price;
                    return price + ' ' + t('display.on') + ' ' + runtime.getDate() + '.' + (runtime.getMonth()+1) + '.' + runtime.getFullYear() + ' ' + runtime.getHours() + ':' + runtime.getMinutes();
                }
            });
        }
    });
    
    // handle events on the buttons
    // fuel, not used
    $('.fuel-buttons button').on('click', document, () => {});
    // time
    $('.time-buttons button').on('click', document, (e) => {
        
        // change active
        $(e.target).addClass('active').siblings().removeClass('active');
        
        // update fuel and time values
        fuel = $('button[data-type="fuel"].active').data('fuel');
        time = $('button[data-type="time"].active').data('period');
        
        // get data from internal api
        fPS.getData(time, (data) => {
            
            // walk through data
            if(data.data.length == 0) {
                $('.mg-display').hide();
            } else {
                $('.mg-display').show();
            }
            for(var station in data.data) {
                
                // get fuel data
                var fuelData = data.data[station][fuel].map((entry) => {
                    return {
                        runtime: new Date(entry.runtime),
                        price: entry.price
                    };
                });
                
                // get id
                var id = station.replace(/[ \.\-\/äöüÄÖÜß]/g, '');
                
                // refresh last runtime and price
                var lRt = fuelData[fuelData.length - 1].runtime;
                $('#' + id + ' span.last-runtime').text(lRt.getDate() + '.' + (lRt.getMonth()+1) + '.' + lRt.getFullYear() + ' ' + lRt.getHours() + ':' + lRt.getMinutes());
                $('#' + id + ' span.last-price').text(fuelData[fuelData.length - 1].price);
                
                // add metrics graphics
                MG.data_graphic({
                    full_width: true,
                    height: 400,
                    target: '#' + id,
                    x_accessor: 'runtime',
                    y_accessor: 'price',
                    min_y_from_data: true,
                    data: fuelData,
                    european_clock: true,
                    y_rollover_format: (data) => {
                        return '';
                    },
                    x_rollover_format: (data) => {
                        var runtime = data.runtime;
                        var price = data.price;
                        return price + ' ' + t('display.on') + ' ' + runtime.getDate() + '.' + (runtime.getMonth()+1) + '.' + runtime.getFullYear() + ' ' + runtime.getHours() + ':' + runtime.getMinutes();
                    }
                });
            }
        });
    });
    // lang
    $('.lang-buttons button').on('click', document, (e) => {
        
        // change active
        $(e.target).addClass('active').siblings().removeClass('active');
        
        // reload page
        window.location.href = '?l=' + encodeURIComponent($(e.target).data('lang'));
    });
});
