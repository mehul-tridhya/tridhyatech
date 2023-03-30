define(['jquery', 'uiComponent', 'ko'], function ($, Component, ko) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Tridhyatech_Knockout/knockout-test-example'
        },
        initialize: function () {
            this.customerName = ko.observableArray([]);
            this.customerData = ko.observable('');
            this.Age = ko.observable('23');
            this.firstName = ko.observable('Mehul');
            this.fullName = ko.computed(function () {
                return this.firstName() + " " + this.Age();
            }, this);
            this.isEnable = false;
            this.isChecked = true;
            this.people = ko.observableArray([]);
            this._super();
            this.isDisplayMessage = false;
            this.isHideName = false;
            this.city = "London",
                this.coords = {
                    latitude: 51.5001524,
                    longitude: -0.1262362
                };
            this.twitterName = ko.observable('@example');
            this.resultData = ko.observable(); // No initial value
            this.suppliers = ['a', 'b', 'c', 'd'];
            this.bins = [1, 2, 3, 4, 5];
            this.name = 'mehul';
            this.manager = { name: 'Upper Manager' };
            this.persons = ko.observableArray([
                { name: 'Franklin', credits: 250 },
                { name: 'Mario', credits: 5800 }
            ]);
            this.buyer = { name: 'Mario', credits: 5800 };
            ko.components.register('calculate-sum', {
                viewModel: function (params) {
                    this.number1 = ko.observable(params && params.number1);
                    this.number2 = ko.observable(params && params.number2);
                    this.result = ko.computed(function () {
                        var sum = Number(this.number1()) + Number(this.number2());
                        if (isNaN(sum))
                            sum = 0;
                        return sum;
                    }, this);
                },
                template: 'Enter Number One: <input data-bind = "value: number1" /> <br> <br>' +
                    ' Enter Number Two: <input data-bind = "value: number2" /> <br> <br>' +
                    ' Sum  = <span data-bind = "text: result" />'
            });
        },
        addNewCustomer: function () {
            this.customerName.push({ name: this.customerData() });
            this.customerData('');
        },

        addNewPeople: function () {
            this.people.push({ firstName: this.firstName(), Age: this.Age() });
            this.firstName('');
            this.Age('');
        },

        getTweets: function () {
            var name = this.twitterName(),
                simulatedResults = [
                    { text: name + ' What a nice day.' },
                    { text: name + ' Building some cool apps.' },
                    { text: name + ' Just saw a famous celebrity eating lard. Yum.' }
                ];
            this.resultData({ retrievalDate: new Date(), topTweets: simulatedResults });
        },
        clearResults: function () {
            this.resultData('');
        },
        someCalculation: function (supplier, bin) {
            return supplier + " " + bin;
        }
    });
}
);