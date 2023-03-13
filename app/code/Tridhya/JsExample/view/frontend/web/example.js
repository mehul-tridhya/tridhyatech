define([
    'jquery',
    'domReady',
], function ($,dom) {
    'use strict';
    return function(config) {
        console.log(config);
        console.log(config.firstname);
        console.log(config.lastname);
    }
});