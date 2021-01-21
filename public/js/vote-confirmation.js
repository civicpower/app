;(function ($, window, document, undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token, jsone) {
                console.log(jsone);
                if(typeof jsone.data.steps.list.email == "boolean"){
                    if(!jsone.data.steps.list.email){
                        $("#receive-email").show();
                    }
                }
                cp_load_user_steps(user_token);
                page_ballot_token = gget("ballot_token");
                $(".ballot_token_param").attr("href","/r/"+String(page_ballot_token));
                get_ballot(page_ballot_token, user_token, jsone);

                setTimeout(function () {
                    cp_dispatch(user_token, [
                        "password",
                    ], "", false);
                }, 2 * 60 * 1000);
                civicpower_erase_cookie("ballot_token");
            },
            null,
            "get_user_info"
        );
    });

    function get_ballot(ballot_token, user_token, jsone_user) {
        api_call(
            "get_ballot_info",
            "post",
            {
                token: user_token,
                ballot_token: ballot_token,
                result: true
            },
            function (jsone) {
                write_ballot(jsone.data);
            }
        );
    }

    function write_ballot(data) {
        if (data.length <= 0) {
            return;
        }
        $(".ballot_end_date").text(data.ballot_end_date_fr);
        $(".ballot_end_hour").text(data.ballot_end_hourmin_fr);
        if (data.ballot_see_results_live == "0") {
            $("#div_see_results_live").remove();
        }
    }
})(jQuery, window, document);
