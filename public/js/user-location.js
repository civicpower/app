;(function ($, window, document, undefined) {
    var timer_zipcode = null;
    var cur_zipcode = "";
    $(document).ready(function () {
        bind_zipcode_change();
        check_user_logged(
            function (user_token, jsone) {
                // cp_dispatch(user_token);
                if(typeof jsone.data.steps.list.city != "undefined" && jsone.data.steps.list.city){
                    $(".menu-hidable").css("visibility","visible");
                }
                cp_load_user_steps(user_token);
                first_focus();
                bind_load_data(jsone.data);
                bind_form(user_token);
                {
                    cp_saved_cookie_data("code_postal", "#input_zipcode", function () {
                        invoke_load_cp(function () {
                            var commune = civicpower_get_cookie("commune");
                            if (typeof commune == "string" && commune.length > 0) {
                                $('#select_city_id option').filter(function () {
                                    return $(this).html().toLowerCase().trim() == commune.toLowerCase().trim();
                                }).attr('selected', 'selected');
                            }
                        });
                    });
                }
            },
            null,
            "get_user_info"
        );
    });


    function bind_load_data(data) {
        if (Object.size(data) > 0) {
            if (typeof data.code_postal !== 'undefined') {
                $("#input_zipcode").val(data.code_postal);
                invoke_load_cp(function () {
                    $("#select_city_id").val(data.user_city_id);
                });
            }
        }
    }

    function bind_form(user_token) {
        $("#form_location").on("submit", function (e) {
            var city_id = $("#select_city_id").val().trim();
            api_call(
                "update_location",
                "post",
                {
                    token: user_token,
                    city_id: city_id
                },
                function (jsone) {
                    if (jsone.status == "success") {
                        //cp_alert("Vos informations ont été enregistrées");
                        //  cp_dispatch(user_token)
                        if (civicpower_referrer("/settings")) {
                            window.location.replace("/settings");
                        } else if(civicpower_cookie_exists("ballot_token")){
                            window.location.replace("/vote?ballot_token="+String(civicpower_get_cookie("ballot_token")));
                        }else{
                            window.location.replace("/ballot-list");
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
            e.preventDefault();
            return false;
        });
    }

    var invoke_load_cp = function (func) {
        var $zip = $("#input_zipcode");
        var zipcode = $zip.val().trim();
        if (cur_zipcode == zipcode) {
            return;
        }
        cur_zipcode = zipcode;
        var $btn_validate = $("#btn_validate");
        var $select = $("#select_city_id");
        var $error_dir = $("#error_div");
        if (!$error_dir.is(":hidden")) {
            $error_dir.slideUp("fast");
        }
        $btn_validate.prop("disabled", true);
        if (!cur_zipcode.match(/\d{5}/)) {
            return;
        }
        clearTimeout(timer_zipcode);
        timer_zipcode = setTimeout(function () {
            $zip.addClass("loading");

            if (zipcode.length !== 5 || zipcode == "00000") {
                $select.slideUp("fast");
                $error_dir.slideDown("fast");
                $zip.removeClass("loading");
                return;
            }
            api_call(
                "get_city",
                "post",
                {
                    zipcode: zipcode
                },
                function (jsone) {
                    $zip.removeClass("loading");
                    if (jsone.status == "success") {
                        $select.empty();
                        if (jsone.data.length > 0) {
                            for (var i in jsone.data) {
                                $select.append('<option value="' + String(jsone.data[i].city_id.trim()) + '">' + String(jsone.data[i].nom_commune.trim()) + '</option>');
                            }
                            if (func != null) {
                                civicpower_mainfunc(func);
                            }
                            $error_dir.slideUp("fast");
                            $select.slideDown("fast");
                            $btn_validate.prop("disabled", false);
                        } else {
                            $select.slideUp("fast");
                            $error_dir.slideDown("fast");
                            $btn_validate.prop("disabled", true);
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
        }, 500);
    };

    function bind_zipcode_change() {
        $("#input_zipcode").on("keyup change", function () {
            invoke_load_cp(null);
        });
    }

    function first_focus() {
        setTimeout(function () {
            $("#input_zipcode").focus();
        }, 250);
    }

})(jQuery, window, document);
