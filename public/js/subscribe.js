;(function ($, window, document, _undefined) {
    var iti = null;
    var subscribe_mode = "phone";
    $(document).ready(function () {
        first_focus();
        check_user_logged(
            function (user_token) {
                cp_load_user_steps(user_token);
                cp_dispatch(user_token);
            },
            function () {
                bind_form_confirm_submit();
                bind_form_subscribe_submit();
                iti = inject_phone_prefix();
                bind_btn_reset();
                bind_btn_connexion();
                bind_btn_change_mode();
                {
                    cp_saved_cookie_data("email", "#input_email");
                    cp_saved_cookie_data("mobile", "#input_phone_mobile");
                }
            }
        );
    });

    function bind_btn_connexion() {
        $("#btn_connexion").on("click",function(e){
            let username = "";
            if(subscribe_mode=="phone"){
                username = $("#input_phone_mobile").val();
            }else{
                username = $("#input_email").val();
            }
            username = username.trim();
            if(username.length > 0){
                civicpower_set_cookie("connect_username",username);
            }
            // alert(username);
            // e.preventDefault();
            // return false;
        });
    }
    function bind_btn_change_mode() {
        $("#btn-change-phone").on("click", function () {
            $("#div-email").slideUp("slow");
            $("#div-mobile").slideDown("slow", function () {
                subscribe_mode = "phone";
                $("#input_phone_mobile").select().focus();
            });
        });
        $("#btn-change-email").on("click", function () {
            $("#div-email").slideDown("slow", function () {
                subscribe_mode = "email";
                $("#input_email").select().focus();
            });
            $("#div-mobile").slideUp("slow");
        });
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
        setTimeout(function () {
            $("#input_phone_mobile").select().focus();
        }, 250);
    }

    function inject_phone_prefix() {
        var input = document.querySelector("#input_phone_mobile");
        return window.intlTelInput(input, {
            initialCountry: "fr",
            preferredCountries: ['fr'],
            utilsScript: "vendor/intl-tel-input-17.0.0/build/js/utils.js",
        });
    }

    function bind_form_confirm_submit() {
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
                        value: subscribe_mode == "phone" ? phone_number_international() : $("#input_email").val().trim(),
                    },
                    function (jsone) {
                        if (jsone.status == "success" && jsone.code == "confirmed") {
                            civicpower_set_cookie(user_cookie_name(), String(jsone.data), 365);
                            civicpower_set_cookie(user_cookie_name_last(), String(jsone.data), 365);
                            cp_dispatch(String(jsone.data));
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


    function local_checked_checkboxes() {
        var checked = true;
        if (!$("#cb_privacy").is(":checked")) {
            checked = false;
        }
        if (!$("#cb_charte").is(":checked")) {
            checked = false;
        }
        return checked;
    }

    function bind_form_subscribe_submit() {
        $("#form_subscribe").on("submit", function (e) {
            if (!local_checked_checkboxes()) {
                cp_alert("Oup's, pour vous inscrire vous devez accepter les conditions d'utilisation !");
                e.preventDefault();
                return false;
            }
            if (subscribe_mode == "phone") {
                $("#input_email").val("");
                var $phone_input = $("#input_phone_mobile");
                // if (!iti.isValidNumber()) {
                if (!civicpower_is_mobile_phone(iti)) {
                    cp_alert("Numéro de téléphone incorrect !\nMerci de respecter le format suivant : " + $phone_input.attr("placeholder"));
                    return false;
                }
                api_call(
                    "subscribe_phone",
                    "post",
                    {
                        dial_code: dial_code(),
                        phone_number_national: phone_number_national(),
                        phone_number_international: phone_number_international(),
                    },
                    function (jsone, status, xhr) {
                        if (jsone.status == "success") {
                            if (jsone.code == "phone_subscribed") {
                                if ((typeof jsone.data == 'string' && jsone.data.length > 0) || typeof jsone.data == 'number') {
                                    cp_alert(jsone.data);
                                }
                                //window.location = "/phone-activation?" + "phone_prefix=" + String(encodeURIComponent(dial_code)) + "&" + "phone=" + String(encodeURIComponent(phone_number_national)) + "&" + "phone_international=" + String(encodeURIComponent(phone_number_international));
                                $("#span_destination").text("sms au");
                                $("#span_phone_number").text(
                                    "(+" + String(dial_code()) + ") " + String(phone_number_national())
                                );
                                $("#box-subscribe").slideUp("slow");
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
            } else {
                $("#input_phone_mobile").val("");
                var $email_input = $("#input_email");
                if (!cp_is_email($email_input.val().trim())) {
                    cp_alert("Adresse email incorrecte !");
                    return false;
                }
                api_call(
                    "subscribe_email",
                    "post",
                    {
                        email: $email_input.val().trim()
                    },
                    function (jsone, status, xhr) {
                        if (jsone.status == "success") {
                            if (jsone.code == "email_subscribed") {
                                if (typeof jsone.data !== 'undefined') {
                                    jsone.data = String(jsone.data);
                                    if (jsone.data.length > 0) {
                                        cp_alert(jsone.data);
                                    }
                                }
                                //window.location = "/phone-activation?" + "phone_prefix=" + String(encodeURIComponent(dial_code)) + "&" + "phone=" + String(encodeURIComponent(phone_number_national)) + "&" + "phone_international=" + String(encodeURIComponent(phone_number_international));
                                $("#span_destination").text("email sur");
                                $("#span_phone_number").text($email_input.val().trim());
                                $("#box-subscribe").slideUp("slow");
                                $("#box-confirm").slideDown("slow", function () {
                                    $("#input_phone_code").focus();
                                    bind_resend_code_reset();
                                });
                            }
                        } else {
                            if (jsone.code == "user_already_exists") {
                                invoke_error_user_already_exists();
                            } else {
                                if (typeof jsone.message != 'undefined') {
                                    cp_alert(jsone.message);
                                } else {
                                    cp_alert("Une erreur est survenue !");
                                }
                            }
                        }
                    }
                );
            }
            e.preventDefault();
            return false;
        });
    }
})(jQuery, window, document);
