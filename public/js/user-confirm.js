;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token, jsone) {
                cp_load_user_steps(user_token);
                $("#span_user_firstname").text(jsone.data.user_firstname);
                get_ballot_list(user_token);
                bind_ballot_click();
            },
            null,
            "get_user_info"
        );
    });

    function bind_ballot_click() {
        $(document).on("click", "button.ballot-item[data-ballot_token]", function () {
            var ballot_token = $(this).attr("data-ballot_token");
            window.location.replace("/vote?ballot_token=" + String(encodeURIComponent(ballot_token)));

        });
    }

    function get_ballot_list(user_token) {
        api_call(
            "get_ballot_list",
            "post",
            {
                token: user_token,
                not_finish: true
            },
            function (jsone) {
                write_ballot_list(jsone.data);
            }
        );
    }

    function write_ballot_list(data) {
        var $item = null;
        for (var i in data) {
            $item = $("#ballot-stock .ballot-item:first").clone();
            $item.find(".img-asker").attr("src","https://"+String(BO_URL)+"/uploads/pp/"+String(data[i].asker_token)+".png?rand="+String(Date.now())+"");
            $item.attr("data-ballot_token", data[i].ballot_token);
            $item.find(".ballot-asker_name").text(data[i].asker_name);
            $item.find(".ballot-title").text(data[i].ballot_title);
            $item.find(".ballot-description").text(data[i].ballot_description);
            $span = $item.find(".vote-info");
            $span.find(".date-start").text(civicpower_datetime_to_date(data[i].ballot_start));
            $span.find(".date-end").text(civicpower_datetime_to_date(data[i].ballot_end));
            $item.appendTo("#ballot_list");
        }
        if($("#ballot_list").find(".ballot-item").length==0){
            $(".div-title-ballot").hide();
        }
    }
})(jQuery, window, document);

