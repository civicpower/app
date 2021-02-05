;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        check_user_logged(
            function (user_token, jsone) {
                cp_load_user_steps(user_token);
                cp_dispatch(user_token, [
                    "city",
                ], "");
                get_ballot_list(user_token,jsone);
                bind_ballot_click();
                if(typeof jsone.data.user_firstname == 'string' && jsone.data.user_firstname.length>0) {
                    $(".user-firstname").text(jsone.data.user_firstname+", ");
                }
            },
            null,
            "get_user_info"
        );
    });

    function local_list_mode(check) {
        var mode = $("#list_mode").val();
        if(typeof check == "undefined" || check == null){
            return mode;
        }else{
            return String(check) === String(mode);
        }
    }
    function bind_ballot_click() {
        $(document).on("click", "button.ballot-item[data-ballot_token]", function () {
            var $item = $(this);
            var ballot_token = $item.attr("data-ballot_token");
            var ballot_shortcode = $item.attr("data-ballot_shortcode");
            var route;
            if(local_list_mode("result")){
                route = "result";
            }else{
                if(local_list_mode("vote")){
                    if($item.attr("data-ballot_running")===1){
                        route = "vote";
                    }else{
                        route = "result";
                    }
                }else{
                    route = "vote";
                }
            }
            if(typeof ballot_shortcode == "string" && ballot_shortcode.length>0 && route === "vote") {
                window.location.replace("/" + String(ballot_shortcode));
            }else{
                window.location.replace("/" + String(route) + "?ballot_token=" + String(encodeURIComponent(ballot_token)));
            }

        });
    }

    function get_ballot_list(user_token,jsone) {

        var mode = local_list_mode();
        var data = {};
        if(mode === "ballot"){
            data = {
                not_finish : true,
                voted : false
            };
        }else if(mode === "vote"){
            if(typeof jsone.data.steps.list.password=="boolean" && !jsone.data.steps.list.password){
                cp_alert("Pour sécuriser vos données, merci de renseigner un mot de passe avant d'accéder à vos votes");
                setTimeout(function () {
                    window.location = "/user-password";
                },3500);
                return;
            }
            data = {
                voted:true
            };
        }else if(mode === "result"){
            data = {
                finish : true
            };
        }
        data.token = user_token;
        api_call(
            "get_ballot_list",
            "post",
            data,
            function (jsone2) {
                write_ballot_list(jsone2.data, mode);
            }
        );
    }


    function write_ballot_list(data, mode) {
        var $item = null;
        if (data.length == 0) {
            $(".no-vote").removeClass("d-none");
            //window.location.replace("/settings");
        } else {
            $(document).on("click","a.url-update",function(e){
                e.stopPropagation();
            });
            for (var i in data) {
                $item = $("#ballot-stock .ballot-item:first").clone();
                $item.attr("data-ballot_running",data[i].ballot_running);
                $item.attr("data-ballot_started",data[i].ballot_started);
                $item.attr("data-ballot_finished",data[i].ballot_finished);
                $item.find(".img-asker").attr("src", "https://"+String(BO_URL)+"/uploads/pp/" + String(data[i].asker_token) + ".png?rand="+String(Date.now())+"");
                $item.attr("data-ballot_token", data[i].ballot_token);
                $item.attr("data-ballot_shortcode", data[i].ballot_shortcode);
                $item.find(".ballot-asker_name").text(data[i].asker_name);
                $item.find(".ballot-title").text(data[i].ballot_title);
                $item.find(".ballot-description").text(data[i].ballot_description);
                $span = $item.find(".vote-info");
                $span.find(".date-start").text(civicpower_datetime_to_date(data[i].ballot_start));
                $span.find(".date-end").text(civicpower_datetime_to_date(data[i].ballot_end));
                $span.find(".nb-participants").text(data[i].nb_participation);
                var status_id = data[i].ballot_bstatus_id;
                $item.find(".ballot-state").text(civipower_status_name(status_id));
                $item.find(".ballot-state").addClass("ballot-state-"+String(status_id));
                $item.appendTo("#ballot_list");
                if(typeof data[i].ballot_share != "undefined" && data[i].ballot_share == 1) {
                    $item.find("a.url-update").each(function () {
                        var $a = $(this);
                        var url = "https://" + String(location.host) + "/" + String(data[i].ballot_shortcode);
                        var text = "Nouvelle consultation de " + data[i].asker_name + " :\n" + data[i].ballot_title;
                        if (data[i].ballot_finished == 1) {
                            text = "Résultats pour la consultation de " + data[i].asker_name + " :\n" + data[i].ballot_title;
                        }
                        var href = $a.attr("href");
                        href = href.replace("__url__", encodeURIComponent(url));
                        href = href.replace("__text__", encodeURIComponent(text));
                        $a.attr("href", href);
                    });
                }else{
                    $item.find(".div-share").remove();
                }
            }
            // $("#ballot_list").find(".ballot-asker_name, .ballot-state, .ballot-description, .ballot-participation").remove();
            $("#ballot_list").find(".ballot-description").remove();

        }
    }
})(jQuery, window, document);
