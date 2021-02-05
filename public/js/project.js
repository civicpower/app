;(function ($, window, document, _undefined) {
    var tmr_resend = null,
        splash_sliding = false,
        splash_zoom = 1;
    $(document).ready(function () {
        remove_old_cookies();
        bind_btn_disconnect();
        top_menu_spacer();
        bind_splash_screen();
        bind_span_demo();
        setTimeout(function () {
            $("#init_hidden").removeClass("d-none");
            $(".small-box-menu-bottom").css("opacity", 1);
            $("body.nobg").removeClass("nobg");
        }, 150)
    });
    function bind_span_demo() {
        if(location.host=="app-demo.civicpower.io"){
            $("#span-demo").removeClass("d-none");
        }
    }
    function remove_old_cookies() {
        civicpower_erase_cookie("user_token");
        civicpower_erase_cookie("user_token_last");
    }
    function top_menu_spacer() {
        $("#small-box-menu-spacer").height(
            $("#small-box-menu").height()
        );
    }

    function local_resize_splash(func) {
        var $div = $(".splash-step-outer").first();
        if ($("#splash-box").height() > window.innerHeight*0.9) {
            splash_zoom *= 0.97;
            $div.css("zoom", splash_zoom);
            setTimeout(function () {
                local_resize_splash(func);
            }, 1);
        } else {
            if (typeof func == 'function') {
                civicpower_mainfunc(func);
            }
        }
    }

    function bind_splash_screen() {
        local_resize_splash(function () {
            if ($("#splash-screen").length > 0) {
                if (civicpower_cookie_exists("splashed")) {
                    civicpower_set_cookie("splashed", true, 365);
                    $("#splash-screen").remove();
                    return;
                }
                $("#splash-screen").css("background-color", "#4055b2");
                if ($(".script-colors").length == 0) {
                    $("body").append('<script type="text/javascript" src="/js/jquery.colors.js" />');
                }
                setTimeout(function () {
                    var $slider = $(".splash-step-inner"),
                        diff = 0,
                        decal = 0;
                    $(".splash-step").width($(".splash-step-outer").width() + "px");
                    $("#splash-screen .small-box").css("opacity", "1");
                    $(document).on("mousedown touchstart", function (e) {
                        var startX = e.pageX || e.originalEvent.touches[0].pageX;
                        var isfirst = $(".splash-step.active").is(":first-child");
                        var islast = $(".splash-step.active").is(":last-child");
                        $(document).on("mousemove touchmove", function (e) {
                            var x = e.pageX || e.originalEvent.touches[0].pageX;
                            var sep = startX - x;
                            var vit = 1.2;
                            if ((isfirst && sep < 0) || (islast && sep > 0)) {
                                vit = 0.25;
                            }
                            diff = -1 * (sep) * vit;// / winW * 20;
                            var nth = parseInt($(".splash-step.active").attr("data-num"));
                            var slider_width = parseFloat($(".splash-step.active").width());
                            decal = parseFloat(-1 * nth * slider_width + diff);
                            $slider.css("margin-left", decal + 'px');
                        });
                    });
                    $(document).on("mouseup touchend", function (e) {
                        $(document).off("mousemove touchmove");
                        if (diff !== 0) {
                            if (diff < 0) {
                                invoke_splash_next_step(1, diff);
                            } else {
                                invoke_splash_next_step(-1, diff);
                            }
                            diff = 0;
                        }
                    });
                }, 255);
                $("#splash-screen #splash-next").on("click", function () {
                    invoke_splash_next_step(1, 0);
                });
                $("#splash-screen .btn-splash").on("click", function () {
                    $("#splash-screen").remove();
                    civicpower_set_cookie("splashed", true, 365);
                    $(document).off("mousemove touchmove mousedown touchstart mouseup touchend");
                });
            }
        });


    }

    function invoke_splash_next_step(dir, diff) {
        if (splash_sliding) {
            return;
        }
        var $step = $(".splash-step.active");
        $nth = parseInt($step.attr("data-num"));
        var $slider = $(".splash-step-inner");
        if (($step.is(":last-child") && dir === 1) || ($step.is(":first-child") && dir === -1)) {
            $slider.animate({"margin-left": '-=' + parseInt(diff) + 'px'}, "fast");
            return;
        }
        var w = $step.outerWidth();
        splash_sliding = true;
        var decalage = ($nth + dir) * w * -1;
        var $spot = $("#splash-screen .dspot.active");
        $spot.removeClass("active");
        if (dir === 1) {
            $spot.addClass("passed");
            $spot.next().addClass("active");
        } else {
            $spot.removeClass("passed");
            $spot.prev().addClass("active");
        }
        $step.removeClass("active");
        var next_bg;
        if (dir === 1) {
            $step.next().addClass("active");
            next_bg = $step.next().attr("data-bg");
        } else {
            $step.prev().addClass("active");
            next_bg = $step.prev().attr("data-bg");
        }
        if ($(".splash-step.active").is(":last-child")) {
            $(".div-slider-text").css("opacity", "0");
        } else {
            $(".div-slider-text").css("opacity", "1");
        }
        $("#splash-screen").animate({
            "background-color": next_bg
        }, "fast");
        $slider.animate(
            {
                "margin-left": parseFloat(decalage) + 'px'
            },
            "fast",
            function () {
                splash_sliding = false;
            }
        );
    }


    function bind_btn_disconnect() {
        $(".btn-disconnect").on("click", function () {
            var cooky = [
                'ballot_token',
                user_cookie_name(),
            ];
            for (var i in cooky) {
                civicpower_erase_cookie(cooky[i]);
            }
            window.location = "/";
        });
    }

})(jQuery, window, document);
var password_min_length = 8;
{
    var code_postal = gget("code_postal");
    var commune = gget("commune");
    var email = gget("email");
    var mobile = gget("mobile");
    var nbdays = 30;
    if (typeof code_postal == "string" && code_postal.length > 0 && !isNaN(code_postal)) {
        civicpower_set_cookie("code_postal", code_postal, nbdays);
    }
    if (typeof commune == "string" && commune.length > 0) {
        civicpower_set_cookie("commune", commune, nbdays);
    }
    if (typeof email == "string" && email.length > 0 && cp_is_email(email)) {
        civicpower_set_cookie("email", email, nbdays);
    }
    if (typeof mobile == "string" && mobile.length > 0 && cp_is_phone(mobile)) {
        civicpower_set_cookie("mobile", mobile, nbdays);
    }
}
function user_cookie_name_last() {
    return "usertoken_last";
}
function user_cookie_name() {
    return "usertoken";
}

function civicpower_referrer(check) {
    try {
        var referrer = new URL(String(document.referrer)).pathname;
        if (typeof check == "undefined" || check === null) {
            return referrer;
        } else {
            return String(referrer) === String(check);
        }
    } catch (nerr) {
        return "";
    }
}

function cp_saved_cookie_data(cookie_name, input_name, func) {
    setTimeout(function () {
        var value = civicpower_get_cookie(cookie_name);
        if (typeof value == "string" && value.length > 0 && $(input_name).val().length == 0) {
            $(input_name).val(value);
            if (typeof func == 'function') {
                civicpower_mainfunc(func);
            }
        }
    }, 500);
}

function api_call(action, mode, params, result_func) {
    var api_endpoint = String(location.host).replace("app", "api");
    $.ajax({
        url: 'https://' + String(api_endpoint) + '/' + String(action),
        type: mode.toUpperCase(),
        data: params,
        dataType: 'json',
        success: result_func,
        error: function (resultat, statut, erreur) {
        },
        complete: function (resultat, statut) {
        }
    });
}

function civicpower_cookie_exists(name) {
    var cookie = civicpower_get_cookie(name);
    return typeof cookie != "undefined" && cookie !== null;
}

function civicpower_set_cookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/;domain=civicpower.io";
}

function civicpower_get_cookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function civicpower_erase_cookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;domain=civicpower.io';
}

function civicpower_user_token() {
    return civicpower_get_cookie(user_cookie_name());
}

function civicpower_redirect_not_logged() {
    //alert("not logged");
    window.location.replace("/subscribe");
}

function check_user_logged(func_connected, func_disconnected, api_route) {
    var user_token = civicpower_user_token();
    if (typeof api_route === 'undefined' || api_route == null) {
        api_route = "check_user_exists";
    }
    if (typeof func_disconnected === 'undefined' || func_disconnected == null) {
        func_disconnected = civicpower_redirect_not_logged;
    }
    if (user_token == null || typeof user_token == 'undefined' || user_token.length <= 2) {
        civicpower_mainfunc(func_disconnected);
    } else {
        api_call(
            api_route,
            "post",
            {
                token: user_token
            },
            function (jsone) {
                if (jsone.status !== "success") {
                    civicpower_mainfunc(func_disconnected);
                } else {
                    if (typeof func_connected != 'undefined' && func_connected != null) {
                        civicpower_mainfunc(func_connected, user_token, jsone);

                    }
                }
            }
        );
    }
}

function civicpower_get_ballot_token(shortcode, func) {
    api_call(
        "get_ballot_token",
        "post",
        {
            shortcode: shortcode
        },
        func
    );
}

function civicpower_must_be_disconnected(redirect_url) {
    check_user_logged(function (user_token) {
        if (typeof redirect_url == 'undefined' || redirect_url == null || redirect_url.trim().length === 0) {
            redirect_url = "/ballot-list";
        }
        window.location.replace(redirect_url);
    });
}

function civicpower_mainfunc(func) {
    func.apply(null, Array.prototype.slice.call(arguments, 1));
}

function gget(key) {
    var url = new URL(window.location.toString());
    var value = url.searchParams.get(key);
    return value;
}

function civipower_status_name(status_id) {
    if (status_id == 10) {
        return "En attente";
    } else if (status_id == 11) {
        return "En cours";
    } else if (status_id == 12) {
        return "Terminé";
    } else {
        return "";
    }
}

function civicpower_datetime_to_date(datetime) {
    if(typeof datetime != "undefined" && datetime != null) {
        var d = Date.createFromMysql(datetime);
        return d.toLocaleDateString('fr-FR') + " à " + d.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});
    }
    return "";
}

Date.createFromMysql = function (mysql_string) {
    var t, result = null;
    if (typeof mysql_string === 'string') {
        t = mysql_string.split(/[- :]/);
        result = new Date(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0);
    }
    return result;
}

function cp_password_check(p,p2) {
    var anUpperCase = /[A-Z]/;
    var aLowerCase = /[a-z]/;
    var aLetter = /[a-zA-Z]/;
    var aNumber = /[0-9]/;
    var aSpecial = /[!|@|#|$|%|^|&|*|(|)|-|_]/;
    var obj = {};
    obj.result = true;

    if (p.length < password_min_length) {
        obj.result = false;
        obj.error = "Mot de passe trop court. " + String(password_min_length) + " caractères minimum.";
        return obj;
    }

    var numUpper = 0;
    var numLower = 0;
    var numNums = 0;
    var numSpecials = 0;
    var numLetters = 0;
    for (var i = 0; i < p.length; i++) {
        if (aLetter.test(p[i]))
            numLetters = 1;
        else if (aNumber.test(p[i]))
            numNums = 1;
        else if (aSpecial.test(p[i]))
            numSpecials = 1;
    }
    var sum = numLetters + numNums + numSpecials;
    if (sum < 2) {
        obj.result = false;
        obj.error = "Merci de choisir un mot de passe plus complexe.\nAu moins une lettre minuscule, une lettre majuscule, un caractère spécial et un chiffre.";
        return obj;
    }else if(p!=p2){
        obj.result = false;
        obj.error = "Les 2 mots de passe sont différents. Merci de corriger";
        return obj;
    }
    return obj;
}

function cp_bind_btn_create_ballot(jsone) {
    if (
        typeof jsone.data.list == "object"
    ) {
        var list = jsone.data.list;
        if (!list.password) {
            $("#link-new-ballot").attr("href", "#").on("click", function () {
                cp_alert("Veuillez choisir un mot de passe pour pouvoir créer une consultation");
                setTimeout(function () {
                    window.location = "/user-password#create-ballot";
                }, 4000);
            });
        } else if (!list.city) {
            $("#link-new-ballot").attr("href", "#").on("click", function () {
                cp_alert("Veuillez renseigner votre ville pour pouvoir créer une consultation");
                setTimeout(function () {
                    window.location = "/user-location";
                }, 4000);
            });
        }
    }
}

function cp_load_user_steps(user_token) {
    api_call(
        "get_user_steps",
        "post",
        {
            token: user_token
        },
        function (jsone) {
            cp_bind_btn_create_ballot(jsone);
            if (jsone.status == "success" && typeof jsone.data !== 'undefined') {
                $(".slider-subscription span").css("width", String(parseFloat(jsone.data.done * 100 / jsone.data.total)) + "%");
                if (jsone.data.done < jsone.data.total) {
                    var dest = null;
                    var routes = {
                        "city": "/user-location",
                        "password": "/user-password",
                        "email": "/user-email",
                        "phone": "/user-phone",
                        "name": "/user-edit",
                    };
                    for (var i in jsone.data.list) {
                        if (!jsone.data.list[i]) {
                            dest = routes[i];
                            break;
                        }
                    }
                    if (dest !== null && typeof dest == "string" && dest.length > 0) {
                        $(".slider-subscription-outer").attr("href", dest);
                    }
                    $(".slider-subscription-outer").slideDown("fast");
                }
            } else {
                if (jsone.message != null && jsone.message.length > 0) {
                    cp_alert(jsone.message);
                } else {
                    cp_alert("Une erreur est survenue !");
                }
            }
        }
    );
}

Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function project_manage_ballot_token(ballot_token) {
    var nbdays_token = 30;
    civicpower_set_cookie("ballot_token", ballot_token, 2);
    api_call(
        "get_ballot_location",
        "post",
        {
            ballot_token: ballot_token
        },
        function (jsone) {
            if (jsone.status == "success") {
                if (typeof jsone.data == "object") {
                    if (typeof jsone.data.city_id != "undefined" && String(jsone.data.city_id).length > 0) {
                        civicpower_set_cookie("commune", jsone.data.city_id, nbdays_token)
                    }
                    if (typeof jsone.data.code_postal != "undefined" && String(jsone.data.code_postal).length > 0) {
                        civicpower_set_cookie("code_postal", jsone.data.code_postal, nbdays_token)
                    }
                }
            } else {
                if (jsone.message != null && jsone.message.length > 0) {
                    cp_alert(jsone.message);
                } else {
                    cp_alert("Une erreur est survenue !");
                }
            }
        }
    );
}

function cp_is_phone(phone) {
    var nb_numeric = (phone.match(/\d/g) || []).length;
    return nb_numeric >= 5;
}

function cp_is_email(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function cp_hide_logged_menu() {
    $("nav.small-box-menu").find("ul.navbar-nav, div#navbar-hover").remove();
    $(".small-box-menu-bottom").remove();
}

function cp_dispatch(user_token, mandatory, route_default, check_nb_login) {
    if (typeof check_nb_login == "undefined" || check_nb_login == null) {
        check_nb_login = true;
    }
    if (route_default == null || typeof route_default == 'undefined') {
        route_default = "/ballot-list";
    }
    if(civicpower_cookie_exists("ballot_token")){
        var bt = civicpower_get_cookie("ballot_token");
        if(typeof bt == "string" && bt.length>0){

            route_default = "/"+String(bt);
        }
    }
    if (mandatory == null || typeof mandatory == 'undefined' || mandatory === "all") {
        mandatory = [
            "password",
            "city",
            "phone",
            "email",
            "name",
            // "address",
        ];
    }
    api_call(
        "get_user_steps",
        "post",
        {
            token: user_token,
            dispatch: true
        },
        function (jsone) {
            if (jsone.status === "success") {
                if (jsone.data != null && typeof jsone.data != 'undefined' && Object.size(jsone.data) > 0) {
                    var list = jsone.data.list;
                    var route = route_default;
                    var nb_login = 0;
                    if (typeof jsone.data.user.user_nb_login == "number") {
                        nb_login = jsone.data.user.user_nb_login;
                    }
                    if (mandatory.indexOf("city") !== -1 && !list.city) {
                        route = "/user-location";
                    } else if (nb_login >= 3 || !check_nb_login) {
                        if (mandatory.indexOf("password") !== -1 && !list.password) {
                            route = "/user-password";
                        } else if (mandatory.indexOf("phone") !== -1 && !list.phone) {
                            route = "/user-phone";
                        } else if (mandatory.indexOf("email") !== -1 && !list.email) {
                            route = "/user-email";
                        } else if (mandatory.indexOf("name") !== -1 && !list.name) {
                            route = "/user-edit";
                        }
                    }
                    if (route !== "") {
                        if (!String(window.location).match(route)) {
                            window.location.replace(route);
                        }
                    }
                }
            } else {
                if (jsone.message != null && jsone.message.length > 0) {
                    cp_alert(jsone.message);
                } else {
                    cp_alert("Une erreur est survenue !");
                }
            }
        }
    );
}

function cp_alert(text) {
    var $modal = $("#modal-alert");
    $modal.find("#p-text").html(text);
    $modal.modal("show");
}

function civicpower_is_mobile_phone(iti) {
    var country = iti.getSelectedCountryData().dialCode;
    if (country == "33") {
        var numero = iti.getNumber();
        return numero.match(/\+33[67]\d{8}/);
    } else {
        return iti.isValidNumber();
    }
}
function bind_resend_code_reset(suite) {
    var $div = $("#timer-resend-code span");
    var $div_outer = $("#div_resend_code");
    var $div_button = $("button#btn_reset");
    if(typeof suite == "undefined"){
        $div_button.prop("disabled",true);
        $div_outer.addClass("blocked");
        $div.text(60);
        tmr_resend = setTimeout(function () {
            bind_resend_code_reset(true);
        }, 1000);
    }else{
        var sec = parseInt($div.text());
        sec--;
        $div.text(sec);
        if(sec===0){
            clearTimeout(tmr_resend);
            tmr_resend = null;
            $div_outer.removeClass("blocked");
            $div_button.prop("disabled",false);
        } else {
            tmr_resend = setTimeout(function () {
                bind_resend_code_reset(true);
            }, 1000);
        }
    }
}

