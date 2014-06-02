var navbar = angular.module('navbar', []);
var messages = angular.module('messages', []);
var profile = angular.module('profile', []);
var search = angular.module('search', []);

angular.module('agApp', [
    'ui.bootstrap',
    'xeditable',
    'ngAnimate',
    'ag.directives.stopEvent',
    'ag.directives.password',
    'ag.directives.focusMe',
    'ag.services.user',
    'navbar',
    'profile',
    'search',
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
}]).run(function(editableOptions, editableThemes) {
  editableThemes.bs3.inputClass = 'input-sm';
  editableThemes.bs3.buttonsClass = 'btn-sm';
  editableOptions.theme = 'bs3';
});


