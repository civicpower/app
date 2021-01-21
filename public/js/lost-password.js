;(function ($, window, document, undefined) {
    $(document).ready(function () {
        first_focus();
        check_user_logged(
            function (user_token) {
                cp_load_user_steps(user_token);
                cp_dispatch(user_token);
            },
            function () {
                bind_form_confirm_submit();
                bind_form_lost_submit();
                bind_btn_reset();
            }
        );
    });
    function bind_btn_reset() {
        $("#btn_reset").on("click", function () {
            $("#box-confirm").slideUp("slow");
            $("#box-subscribe").slideDown("slow", function () {
                $("#input_phone_mobile").select().focus();
            });
        });
    }

    function bind_form_lost_submit() {
        $("#form_lost").on("submit", function (e) {
            var $input_username = $("#input_username");
            var username = $input_username.val();
            if (!cp_is_email(username) && !cp_is_phone(username)) {
                cp_alert("Adresse email ou numéro de mobile invalide");
                return false;
            }
            api_call(
                "lost_password",
                "post",
                {
                    username: username,
                },
                function (jsone, status, xhr) {
                    if (jsone.status == "success") {
                        if (jsone.code == "lost_password") {
                            if (typeof jsone.data !== 'undefined' && jsone.data.trim().length > 0) {
                                cp_alert(jsone.data);
                            }
                            $("#box-subscribe").slideUp("slow");
                            $("#box-confirm").slideDown("slow", function () {
                                $("#input_phone_code").focus();
                            });
                        }
                    } else {
                        if (jsone.code == "user_not_exists") {
                            invoke_error_user_does_not_exist();
                        }
                    }
                }
            );
            e.preventDefault();
            return false;
        });
    }

    function invoke_error_user_does_not_exist() {
        $('#modal_user_does_not_exist').modal('show');
    }

    function first_focus() {
        setTimeout(function () {
            $("#input_username").focus();
        }, 250);
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
                var mode = cp_is_phone(username)?"phone":"email";
                api_call(
                    "validate",
                    "post",
                    {
                        code: code,
                        mode: mode,
                        value: username,
                    },
                    function (jsone) {
                        if (jsone.status == "success" && jsone.code == "confirmed") {
                            civicpower_set_cookie(user_cookie_name(), String(jsone.data), 365);
                            civicpower_set_cookie(user_cookie_name_last(), String(jsone.data), 365);
                            //cp_dispatch(String(jsone.data));
                            cp_alert("Veuillez choisir un nouveau mot de passe");
                            setTimeout(function (){
                                window.location = "/user-password";
                            },2000);
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

})(jQuery, window, document);
