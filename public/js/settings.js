;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token, jsone) {
                cp_load_user_steps(user_token);
                bind_membership(user_token, jsone.data);
            },
            null,
            "get_user_info"
        );
    });

    function bind_membership(user_token,user) {
        if(user.member_status_id==1 || user.member_status_id==2){
            $("#cb_membership").prop("checked",true);
        }
        $("#cb_membership").on("change", function () {
            var $input = $(this);
            var value = $input.is(":checked") ? 1 : 0;
            api_call(
                "set_membership",
                "post",
                {
                    token: user_token,
                    value: value
                },
                function (jsone) {
                    if (parseInt(value) == 1) {
                        // cp_alert("Votre demande d'adhésion a été enregistrée");
                    }
                }
            );
        });
    }
})(jQuery, window, document);
