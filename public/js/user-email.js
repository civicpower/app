;(function ($, window, document, _undefined) {
    var subscribe_mode = "email";
    $(document).ready(function () {
        first_focus();
        check_user_logged(
            function (user_token,data) {
                cp_load_user_steps(user_token);
                cp_dispatch(user_token,[
                    "city"
                ],"");
                bind_fill(data);
                bind_form_subscribe_submit(user_token);
                bind_form_confirm_submit(user_token);
                bind_btn_reset();
            },
            null,
            "get_user_info"
        );
    });

    function bind_fill(data) {
        var user_email = String(data.data.user_email);
        if(user_email.length>2) {
            $("#input_email").val(user_email);
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
        setTimeout(function () {
            $("#input_email").select().focus();
        }, 250);
    }

    function bind_form_confirm_submit(user_token) {
        $("#form_phone_activation").on("submit", function (e) {
            var code = $("#input_phone_code").val().trim();
            if (!code.match(/\d{4}/)) {
                cp_alert("Ce code est incorrect !");
                $("#input_phone_code").focus();
            } else {
                var $email_input = $("#input_email");
                var email = $email_input.val().trim();
                api_call(
                    "validate",
                    "post",
                    {
                        code: code,
                        mode: subscribe_mode,
                        value: email,
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

    function invoke_error_user_already_exists() {
        $('#modal_user_exists').modal('show');
    }

    function bind_form_subscribe_submit(user_token) {
        $("#form_subscribe").on("submit", function (e) {
            var $email_input = $("#input_email");
            var email = $email_input.val().trim();
            if (!cp_is_email(email)) {
                cp_alert("Adresse email incorrecte !");
                return false;
            }
            api_call(
                "update_email",
                "post",
                {
                    email: email,
                    token:user_token
                },
                function (jsone, status, xhr) {
                    if (jsone.status == "success") {
                        if (jsone.code == "email_subscribed") {
                            if ((typeof jsone.data == 'string' && jsone.data.length>0) || typeof jsone.data == 'number') {
                                cp_alert(jsone.data);
                            }
                            //window.location = "/phone-activation?" + "phone_prefix=" + String(encodeURIComponent(dial_code)) + "&" + "phone=" + String(encodeURIComponent(phone_number_national)) + "&" + "phone_international=" + String(encodeURIComponent(phone_number_international));
                            $("#span_phone_number").text(
                                String(email)
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
                        }else{
                            if(typeof jsone.message != 'undefined'){
                                cp_alert(jsone.message);
                            }else{
                                cp_alert("Une erreur est survenue !");
                            }
                        }
                    }
                }
            );
            e.preventDefault();
            return false;
        });
    }
})(jQuery, window, document);
