;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token, jsone) {
                cp_dispatch(user_token);
            },
            function(){
            },
            "get_user_info"
        );
    });
})(jQuery, window, document);
