;(function ($, window, document, undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token, jsone) {
                cp_load_user_steps(user_token);
                $(".if-not-connected").remove();
                window.location = "/ballot-list";
                $("#span_user_firstname").text(jsone.data.user_firstname);
                return;
                get_ballot_list(user_token);
                bind_ballot_click();
            },
            function(){
                $(".if-connected").remove();
                bind_ballot_cookie();
            },
            "get_user_info"
        );
    });

    function bind_ballot_cookie() {
        var ballot_token = civicpower_get_cookie("ballot_token");
        if (typeof ballot_token == "string" && ballot_token.length > 0) {
            api_call(
                "get_ballot_info",
                "post",
                {
                    ballot_token: ballot_token
                },
                function (jsone) {
                    var show_ballot = false;
                    if(typeof jsone.data.asker_token == "string" && jsone.data.asker_token.length>0){
                        $("#custom-yes img.img-asker").attr("src", "https://" + String(BO_URL) + "/uploads/pp/" + String(jsone.data.asker_token) + ".png?rand="+String(Math.random()*9999)+"");
                    }else{
                        $("#custom-yes img.img-asker").remove();
                    }
                    if(typeof jsone.data.asker_name == "string" && jsone.data.asker_name.length>0){
                        $("#custom-yes .ballot-asker_name").text(jsone.data.asker_name);
                    }else{
                        $("#custom-yes .ballot-asker_name").remove();
                    }
                    if(typeof jsone.data.ballot_title == "string" && jsone.data.ballot_title.length>0){
                        show_ballot = true;
                        $("#custom-yes .ballot-title").text(jsone.data.ballot_title);
                    }else{
                        $("#custom-yes .ballot-title").remove();
                    }
                    if(typeof jsone.data.ballot_description == "string" && jsone.data.ballot_description.length>0){
                        show_ballot = true;
                        $("#custom-yes .ballot-description").text(jsone.data.ballot_description);
                    }else{
                        $("#custom-yes .ballot-description").remove();
                    }
                    $("#custom-yes").find(".date-start").text(civicpower_datetime_to_date(jsone.data.ballot_start));
                    $("#custom-yes").find(".date-end").text(civicpower_datetime_to_date(jsone.data.ballot_end));
                    $("#custom-yes").find(".nb-participants").text(jsone.data.nb_participation);
                    if(show_ballot){
                        $("#custom-yes").show();
                        $("#custom-no").hide();
                    }
                }
            );
        }
    }



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
            $item.find(".img-asker").attr("src", "https://" + String(BO_URL) + "/uploads/pp/" + String(data[i].asker_token) + ".png?rand="+String(Math.random()*9999)+"");
            $item.attr("data-ballot_token", data[i].ballot_token);
            $item.find(".ballot-asker_name").text(data[i].asker_name);
            $item.find(".ballot-title").text(data[i].ballot_title);
            $item.find(".ballot-description").text(data[i].ballot_description);
            $span = $item.find(".vote-info");
            $span.find(".date-start").text(civicpower_datetime_to_date(data[i].ballot_start));
            $span.find(".date-end").text(civicpower_datetime_to_date(data[i].ballot_end));
            $item.appendTo("#ballot_list");
        }
        if ($("#ballot_list").find(".ballot-item").length == 0) {
            $(".div-my-account").show();
        }
    }
})(jQuery, window, document);
