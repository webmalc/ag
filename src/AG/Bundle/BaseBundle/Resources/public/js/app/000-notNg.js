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
    $.mask.definitions['l'] = "[A-Za-zА-яА-я[;'.,]";
    $('.car-number').groupinputs();
    $('.car-number-1').mask("l");
    $('.car-number-2').mask("999");
    $('.car-number-3').mask("ll");
    $('.car-number-4').mask("99?9");

    $('.input-transliterate').change(function() {

        var text = $(this).val(),
                trans = function(text) {

                    replacer = {
                        "q": "й", "w": "ц", "e": "у", "r": "к", "t": "е", "y": "н", "u": "г",
                        "i": "ш", "o": "щ", "p": "з", "[": "х", "]": "ъ", "a": "ф", "s": "ы",
                        "d": "в", "f": "а", "g": "п", "h": "р", "j": "о", "k": "л", "l": "д",
                        ";": "ж", "'": "э", "z": "я", "x": "ч", "c": "с", "v": "м", "b": "и",
                        "n": "т", "m": "ь", ",": "б", ".": "ю", "/": ".", "_": "_"
                    };

                    return text.replace(/[A-z/,.;\'\]\[]/g, function(x) {
                        return x === x.toLowerCase() ? replacer[ x ] : replacer[ x.toLowerCase() ].toUpperCase();
                    });
                };

        $(this).val(trans(text));
    });
});
