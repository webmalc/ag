var navbar = angular.module('navbar', []);
var messages = angular.module('messages', []);

angular.module('agApp', [
    'ui.bootstrap',
    'ngAnimate',
    'ag.directives.dropdown',
    'ag.services.user',
    'navbar',
    'messages'
]).config(['$httpProvider', '$interpolateProvider', function ($httpProvider, $interpolateProvider) {
    'use strict';

    $httpProvider.
        defaults.
        headers.
        common["X-Requested-With"] = 'XMLHttpRequest';

    $interpolateProvider.
        startSymbol('{[{').
        endSymbol('}]}');
}]);


