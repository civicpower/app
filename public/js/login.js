;(function ($, window, document, _undefined) {
    var subscribe_mode = "phone";
    $(document).ready(function () {
        first_focus();
        check_user_logged(
            function (user_token) {
                cp_load_user_steps(user_token);
                cp_dispatch(user_token);
                //window.location.replace("/ballot-list");
            },
            function () {
                bind_form_confirm_submit();
                bind_form_login_submit();
                bind_hide_error();
                bind_btn_continue();
                bind_username_keyup();
                bind_btn_reset();
                bind_cookies_connexion();
            }
        );
    });

    function bind_cookies_connexion() {
        if(civicpower_cookie_exists("connect_username")){
            let username = civicpower_get_cookie("connect_username");
            $("#input_username").val(username.trim());
            civicpower_erase_cookie("connect_username");
        }
    }
    function bind_username_keyup() {
        $("#input_username").on("keyup", function () {
            $("#div-btn-connect-inner").slideUp("fast");
            $("#div-password-inner").slideUp("fast");
        });
    }

    function bind_btn_reset() {
        $("#btn_reset").on("click", function () {
            $("#box-confirm").slideUp("fast");
            $("#box-subscribe").slideDown("fast", function () {
                $("#input_username").select().focus();
            });
        });
    }

    function bind_form_confirm_submit() {
        $("#form_phone_activation").on("submit", function (e) {
            var code = $("#input_phone_code").val().trim();
            if (!code.match(/\d{4}/)) {
                cp_alert("Ce code est incorrect !");
                $("#input_phone_code").focus();
            } else {
                var $input_username = $("#input_username");
                var username = $input_username.val();
                var mode = cp_is_phone(username) ? "phone" : "email";
                api_call(
                    "validate",
                    "post",
                    {
                        code: code,
                        mode: mode,
                        value: local_remove_space(username),
                    },
                    function (jsone) {
                        if (jsone.status == "success" && jsone.code == "confirmed") {
                            civicpower_set_cookie(user_cookie_name(), String(jsone.data), 365);
                            civicpower_set_cookie(user_cookie_name_last(), String(jsone.data), 365);
                            cp_dispatch(String(jsone.data))
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

    function local_remove_space(str) {
        return str.replace(/ /g, '');
    }

    function invoke_continue() {
        if (local_check_username()) {
            var username = $("#input_username").val();
            api_call(
                "check_account_password",
                "post",
                {
                    username: local_remove_space(username)
                },
                function (jsone) {
                    if (jsone.status == "success") {
                        var passlen = parseInt(jsone.data);
                        if (jsone.message == "yes") {
                            $("#div-btn-connect-inner").slideDown("fast");
                            $("#div-password-inner").slideDown("fast", function () {
                                $("#input_password").focus();
                            });
                        } else {
                            if (typeof jsone.data !== 'undefined' && String(jsone.data).trim().length > 0) {
                                cp_alert(jsone.data);
                            }
                            $("#box-subscribe").slideUp("fast");
                            $("#box-confirm").slideDown("fast", function () {
                                $("#input_phone_code").focus();
                                bind_resend_code_reset();
                            });
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
    }

    function bind_btn_continue() {
        $("#btn-continue").on("click", function () {
            invoke_continue();
        });
        $("#input_username").on("keyup", function (e) {
            if (e.keyCode == 13) {
                invoke_continue();
            }
        });
    }

    function bind_hide_error() {
        $("#input_username,#input_password").on("change keyup paste", function () {
            local_error();
        });
    }

    function first_focus() {
        setTimeout(function () {
            $("#input_username").select().focus();
        }, 250);
    }

    function local_check_username() {
        var res = true;
        var username = $("#input_username").val();
        if (username.length <= 5) {
            local_error("Veuillez renseigner votre email ou votre numéro de mobile.");
            res = false;
        } else {
            if (!cp_is_email(username) && !cp_is_phone(username)) {
                local_error("Format d'email ou de numéro de mobile incorrect");
                res = false;
            }
        }
        return res;
    }

    function local_check_password() {
        var res = true;
        var password = $("#input_password").val();
        if (password.length < password_min_length) {
            local_error("Format de mot de passe incorrect");
            res = false;
        }
        return res;
    }
    function local_try_login() {
        var username = $("#input_username").val();
        var password = $("#input_password").val();
        if (username.length <= 5) {
            local_error("Veuillez renseigner votre email ou votre numéro de mobile.");
        } else {
            if (!cp_is_email(username) && !cp_is_phone(username)) {
                local_error("Format d'email ou de numéro de mobile incorrect");
            } else {
                if (password.length < password_min_length) {
                    local_error("Format de mot de passe incorrect");
                } else {
                    api_call(
                        "login",
                        "post",
                        {
                            username: local_remove_space(username),
                            password: sha1(password),
                        },
                        function (jsone) {
                            if (jsone.status == "success") {
                                civicpower_set_cookie(user_cookie_name(), String(jsone.data), 365);
                                civicpower_set_cookie(user_cookie_name_last(), String(jsone.data), 365);
                                setTimeout(function () {
                                    cp_dispatch(String(jsone.data));
                                    //window.location = "/user-location";
                                }, 100);
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
            }
        }
    }

    function bind_form_login_submit() {
        $("#input_password").on("keyup", function (e) {
            if (e.keyCode == 13) {
                local_try_login();
            }
        });
        $("#btn_send_code").on("click", function (e) {
            local_try_login();
            e.preventDefault();
            return false;
        });
    }

    function local_error(text) {
        var $div = $("#error_div");
        if (text !== null && typeof text !== 'undefined') {
            $div.text(text);
            if ($div.is(":hidden")) {
                $div.slideDown("fast");
            }
        } else {
            $div.text("");
            if (!$div.is(":hidden")) {
                $div.slideUp("fast");
            }
        }
    }
})(jQuery, window, document);
