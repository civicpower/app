;(function ($, window, document, _undefined) {
    var iti = null;
    var subscribe_mode = "phone";
    $(document).ready(function () {
        first_focus();
        check_user_logged(
            function (user_token, data) {
                cp_load_user_steps(user_token);
                //cp_dispatch(user_token);
                bind_fill(data);
                bind_form_subscribe_submit(user_token);
                bind_form_confirm_submit(user_token);
                iti = inject_phone_prefix();
                bind_btn_reset();
            },
            null,
            "get_user_info"
        );
    });


    function bind_fill(data) {
        var user_phone_national = String(data.data.user_phone_national);
        if (user_phone_national.length > 2) {
            $("#input_phone_mobile").val(user_phone_national);
        }
    }

    function bind_btn_reset() {
        $("#btn_reset").on("click", function () {
            $("#box-confirm").slideUp("slow");
            $("#box-subscribe").slideDown("slow", function () {
                $("#input_phone_mobile").select().focus();
            });
        });
    }

    function first_focus() {
        $("#input_phone_mobile").select().focus();
        setTimeout(function () {
            $("#input_phone_mobile").select().focus();
        }, 100);
    }

    function inject_phone_prefix() {
        var input = document.querySelector("#input_phone_mobile");
        return window.intlTelInput(input, {
            initialCountry: "fr",
            preferredCountries: ['fr'],
            utilsScript: "vendor/intl-tel-input-17.0.0/build/js/utils.js",
        });
    }

    function bind_form_confirm_submit(user_token) {
        $("#form_phone_activation").on("submit", function (e) {
            var code = $("#input_phone_code").val().trim();
            if (!code.match(/\d{4}/)) {
                cp_alert("Ce code est incorrect !");
                $("#input_phone_code").focus();
            } else {
                api_call(
                    "validate",
                    "post",
                    {
                        code: code,
                        mode: subscribe_mode,
                        value: phone_number_international(),
                    },
                    function (jsone) {
                        if (jsone.status == "success" && jsone.code == "confirmed") {
                            civicpower_set_cookie(user_cookie_name(), String(jsone.data), 365);
                            civicpower_set_cookie(user_cookie_name_last(), String(jsone.data), 365);
                            setTimeout(function () {
                                if (civicpower_referrer("/settings")) {
                                    window.location.replace("/settings");
                                } else if (civicpower_cookie_exists("ballot_token")) {
                                    window.location.replace("/vote?ballot_token=" + String(civicpower_get_cookie("ballot_token")));
                                } else {
                                    window.location.replace("/ballot-list");
                                }
                                //cp_alert("Vos informations ont été enregistrées");
                                // cp_dispatch(user_token);
                            }, 100);
                        } else {
                            cp_alert("Ce code est incorrect !");
                            $("#input_phone_code").focus();
                        }
                    }
                );
            }
            e.preventDefault();
            return false;
        });
    }

    function dial_code() {
        return iti.getSelectedCountryData().dialCode;
    }

    function phone_number_international() {
        return iti.getNumber(intlTelInputUtils.numberFormat.E164);
    }

    function invoke_error_user_already_exists() {
        $('#modal_user_exists').modal('show');
    }

    function phone_number_national() {
        return iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);
    }


    function bind_form_subscribe_submit(user_token) {
        $("#form_subscribe").on("submit", function (e) {
            var $phone_input = $("#input_phone_mobile");
            // if (!iti.isValidNumber()) {
            if (!civicpower_is_mobile_phone(iti)) {
                cp_alert("Numéro de téléphone incorrect !\nMerci de respecter le format suivant : " + $phone_input.attr("placeholder"));
                return false;
            }
            api_call(
                "update_phone",
                "post",
                {
                    dial_code: dial_code(),
                    phone_number_national: phone_number_national(),
                    phone_number_international: phone_number_international(),
                    token: user_token
                },
                function (jsone, status, xhr) {
                    if (jsone.status == "success") {
                        if (jsone.code == "phone_subscribed") {
                            if ((typeof jsone.data == 'string' && jsone.data.length > 0) || typeof jsone.data == 'number') {
                                cp_alert(jsone.data);
                            }
                            //window.location = "/phone-activation?" + "phone_prefix=" + String(encodeURIComponent(dial_code)) + "&" + "phone=" + String(encodeURIComponent(phone_number_national)) + "&" + "phone_international=" + String(encodeURIComponent(phone_number_international));
                            $("#span_phone_number").text(
                                "(+" + String(dial_code()) + ") " + String(phone_number_national())
                            );
                            $("#box-subscribe").slideUp("slow");
                            // return;
                            $("#box-confirm").slideDown("slow", function () {
                                $("#input_phone_code").focus();
                                bind_resend_code_reset();
                            });
                        }
                    } else {
                        if (jsone.code == "user_already_exists") {
                            invoke_error_user_already_exists();
                        } else if (jsone.code == "not_mobile") {
                            cp_alert("Seuls les numéros de mobile en 06 et 07 sont acceptés");
                        }
                    }
                }
            );
            e.preventDefault();
            return false;
        });
    }
})(jQuery, window, document);
