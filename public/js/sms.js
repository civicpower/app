;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        bind_init();
    });

    function bind_init() {
        var ml_ballot_token = civicpower_get_cookie("ml_ballot_token");
        if (typeof ml_ballot_token == "string" && ml_ballot_token.length > 0) {
            api_call(
                "get_ballot_info",
                "post",
                {
                    ballot_token: ml_ballot_token
                },
                function (jsone) {
                    if (typeof jsone.data == "object") {
                        var data = jsone.data;
                        if (typeof data.ballot_id != "undefined") {
                            var show_ballot = false;
                            if ((typeof data.user_firstname == "string" && data.user_firstname.length > 0) || (typeof data.user_lastname == "string" && data.user_lastname.length > 0)) {
                                $("#inviter-name").text(data.user_firstname + " " + data.user_lastname);
                            }
                            if (typeof data.asker_token == "string" && data.asker_token.length > 0) {
                                $("#custom-yes img.img-asker").attr("src", "https://" + String(BO_URL) + "/uploads/pp/" + String(data.asker_token) + ".png?rand=" + String(Date.now()) + "");
                            } else {
                                $("#custom-yes img.img-asker").remove();
                            }
                            if (typeof data.asker_name == "string" && data.asker_name.length > 0) {
                                $("#custom-yes .ballot-asker_name").text(data.asker_name);
                            } else {
                                $("#custom-yes .ballot-asker_name").remove();
                            }
                            if (typeof data.ballot_title == "string" && data.ballot_title.length > 0) {
                                show_ballot = true;
                                $("#custom-yes .ballot-title").text(data.ballot_title);
                            } else {
                                $("#custom-yes .ballot-title").remove();
                            }
                            if (typeof data.ballot_description == "string" && data.ballot_description.length > 0) {
                                show_ballot = true;
                                $("#custom-yes .ballot-description").text(data.ballot_description);
                            } else {
                                $("#custom-yes .ballot-description").remove();
                            }
                            if (typeof data.ballot_shortcode == "string") {
                                $(document).on("click","#custom-yes",function(){
                                    window.location.replace("/"+String(data.ballot_shortcode));
                                });
                            }
                            $("#custom-yes").find(".date-start").text(civicpower_datetime_to_date(data.ballot_start));
                            $("#custom-yes").find(".date-end").text(civicpower_datetime_to_date(data.ballot_end));
                            $("#custom-yes").find(".nb-participants").text(data.nb_participation);
                            if (show_ballot) {
                                $("#custom-yes").show();
                                $("#custom-no").hide();
                            }
                        }
                    }
                }
            );
        }
    }
})(jQuery, window, document);
