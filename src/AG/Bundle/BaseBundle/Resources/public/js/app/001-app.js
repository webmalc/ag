var navbarApp = angular.module('navbarApp', []);

angular.module('agApp', [
    'ui.bootstrap',
    'ag.directives.dropdown',
    'ag.directives.formAutofillFix',
    'navbarApp'
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


