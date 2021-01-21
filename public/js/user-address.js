;(function ($, window, document, undefined) {
    window.location.replace("/ballot-list");
    return;
    var timer_zipcode = null;
    var cur_zipcode = "";
    $(document).ready(function () {
        bind_zipcode_change();
        check_user_logged(
            function (user_token, jsone) {
                cp_load_user_steps(user_token);
                bind_load_data(jsone.data);
                bind_form_user_edit_submit(user_token);
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
            $("#input_streetnum").val(data.user_streetnum);
            $("#input_street").val(data.user_street);
        }
    }


    function bind_zipcode_change() {
        $("#input_zipcode").on("keyup change", function () {
            invoke_load_cp(null);
        });
    }


    function bind_form_user_edit_submit(user_token) {
        $("#form_user_edit").on("submit", function (e) {
            var streetnum = $("#input_streetnum").val().trim();
            var street = $("#input_street").val().trim();
            var city_id = $("#select_city_id").val();

            var params = {
                token: user_token
            };
            var error = [];
            if (streetnum!== null && typeof streetnum !== "undefined" && streetnum.length>50) {
                error.push("Le n° de rue est incorrect !");
            } else {
                params.streetnum = streetnum;
            }
            if (street.length <= 0 || street.length > 50) {
                error.push("Le nom de rue est incorrect !");
            } else {
                params.street = street;
            }
            if (isNaN(city_id) || city_id <=0) {
                error.push("La commune est incorrecte !");
            } else {
                params.city_id = city_id;
            }
            if (error.length > 0) {
                cp_alert(error.join("\n"));
            } else {
                api_call(
                    "update_address",
                    "post",
                    params,
                    function (jsone) {
                        if (jsone.status == "success") {
                            window.location.replace("/settings");
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
        var $div_select = $("#div_city_id");
        var $select = $("#select_city_id");
        var $error_dir = $("#error_div");
        if (!$error_dir.is(":hidden")) {
            $error_dir.slideUp("fast");
        }
        $btn_validate.prop("disabled", true);
        clearTimeout(timer_zipcode);
        timer_zipcode = setTimeout(function () {
            $zip.addClass("loading");

            if (zipcode.length !== 5) {
                $div_select.slideUp("slow");
                $error_dir.slideDown("slow");
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
                                if(jsone.data[i] === null || typeof jsone.data[i] == 'undefined' || jsone.data[i] === undefined){
                                    continue;
                                }
                                $select.append('<option value="' + String(jsone.data[i].city_id.trim()) + '">' + String(jsone.data[i].nom_commune.trim()) + '</option>');
                            }
                            if (func != null) {
                                civicpower_mainfunc(func);
                            }
                            $error_dir.slideUp("fast");
                            $div_select.slideDown("fast");
                            $btn_validate.prop("disabled", false);
                        } else {
                            $div_select.slideUp("fast");
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

})(jQuery, window, document);
