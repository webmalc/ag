var navbar = angular.module('navbar', []);
var messages = angular.module('messages', []);
var profile = angular.module('profile', []);

angular.module('agApp', [
    'ui.bootstrap',
    'ngAnimate',
    'ag.directives.stopEvent',
    'ag.directives.password',
    'ag.directives.focusMe',
    'ag.services.user',
    'navbar',
    'profile',
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


