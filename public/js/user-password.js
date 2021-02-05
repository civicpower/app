;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token) {
                // cp_dispatch(user_token);
                cp_load_user_steps(user_token);
                first_focus();
                bind_form(user_token);
                bind_btn_eye();
            },
            null
        );
    });

    function bind_btn_eye() {
        $(".btn-eye").on("click", function () {
            var $input = $(this).closest(".div-password").find("input.input-pass");
            var len = $input.val().length;
            if ($input.attr("type") == "password") {
                $input.attr("type", "text");
            } else {
                $input.attr("type", "password");
            }
            $input.focus();
            $input[0].setSelectionRange(len, len);
        });
    }

    function bind_form(user_token) {
        $("#input_password").on("keyup change", function (e) {
            var $error_dir = $("#div-error");
            if (!$error_dir.is(":hidden")) {
                $error_dir.slideUp("fast");
            }
        });
        $("#form_password").on("submit", function (e) {
            var $input = $("#input_password");
            var $input2 = $("#input_password2");
            var password = $input.val();
            var password2 = $input2.val();
            var check = cp_password_check(password,password2);
            if (check.result === false) {
                $("#div-error").text(check.error).slideDown("fast");
            } else {
                api_call(
                    "set_password",
                    "post",
                    {
                        token: user_token,
                        password: sha1(password)
                    },
                    function (jsone) {
                        if (jsone.status === "success") {
                            if(top.location.hash == "#create-ballot"){
                                window.location.replace($("#link-new-ballot").attr("data-url"));
                            } else if (civicpower_referrer("/settings")) {
                                window.location.replace("/settings");
                            } else if (civicpower_cookie_exists("ballot_token")) {
                                window.location.replace("/vote?ballot_token=" + String(civicpower_get_cookie("ballot_token")));
                            } else {
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
            }
            e.preventDefault();
            return false;
        });
    }

    function first_focus() {
        setTimeout(function () {
            $("#input_password").focus();
        }, 250);
    }

})(jQuery, window, document);
