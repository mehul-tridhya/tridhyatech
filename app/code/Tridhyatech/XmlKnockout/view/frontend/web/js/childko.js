define([
    'jquery',
    'uiComponent',
    'ko',
], function ($, Component, ko) {
    'use strict';
    return Component.extend({
        // defaults: {
        //     template: 'Tridhyatech_XmlKnockout/child-ko'
        // },
        getChildText: function () {
            return "text from child js";
        },
    });
});