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
    $.mask.definitions['l'] = "[A-Za-z0-9АВЕКМНОРСТУХавекмнорстух]";
    $('.car-number').mask("llllll?llll", {placeholder:" "});
    $('.car-number').keyup(function(){
        $('.car-number').trigger('change');
        
    });

    //Search SMS
    $("#sendSmsTеxt").mouseup(function(e){
	e.preventDefault();
    });
    $('#sendSmsTеxt').focus(function () {
        $(this).select();
        $(this).setSelectionRange(0, 9999);
    });
    
    
});
