/*global uLogin*/

$(document).ready(function() {
    //Ulogin
    if ($('#ulogin-navbar-login').length) {
        uLogin.customInit("ulogin-navbar-login");
    }
    if ($('#ulogin-navbar-register').length) {
        uLogin.customInit("ulogin-navbar-register");
    }
    if ($('#ulogin-page-login').length) {
        uLogin.customInit("ulogin-page-login");
    }
    if ($('#ulogin-page-register').length) {
        uLogin.customInit("ulogin-page-register");
    }

    //Car number
    $.mask.definitions['l'] = "[А-Яа-я]";
    ;
    $('.car-number').groupinputs();
    $('.car-number-1').mask("l");
    $('.car-number-2').mask("999");
    $('.car-number-3').mask("ll");
    $('.car-number-4').mask("99?9");
});
