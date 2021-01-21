;(function ($, window, document, undefined) {
    $(document).ready(function () {
        bind_init();
        bind_submit();
    });

    function bind_submit() {
        $("#btn-submit").on("click", function () {
            var to = $("#to-value").text();
            if(typeof to == "string" && to.length>0) {
                var data_form = $("#form_unsubscribe").serializeArray();
                var data=[];
                for(var i in data_form){
                    data.push(data_form[i].name);
                }
                api_call(
                    "mail_unsubscribe",
                    "post",
                    {
                        to: to,
                        list: data
                    },
                    function (jsone) {
                        window.location.replace("/ballot-list");
                    }
                );
            }else{
                window.location.replace("/ballot-list");
            }
        });
    }

    function bind_init() {
        var mlto = civicpower_get_cookie("ml");
        var ml_ballot_token = civicpower_get_cookie("ml_ballot_token");
        if (typeof mlto == "string" && mlto.length > 0) {
            $("#to-value").text(mlto);
            if (mlto.match(/@/)) {
                $("#to-label").text("Votre adresse email :");
            } else {
                $("#to-label").text("Votre numéro de mobile :");
            }

            if (typeof ml_ballot_token == "string" && ml_ballot_token.length > 0) {
                api_call(
                    "get_ballot_info",
                    "post",
                    {
                        ballot_token: ml_ballot_token
                    },
                    function (jsone) {
                        if (typeof jsone.data.asker_name == "string" && jsone.data.asker_name.length > 0) {
                            $("#asker-name").text(jsone.data.asker_name);
                            $("#input_asker").attr("name", String(ml_ballot_token));
                            $("#item-asker").removeClass("d-none");
                        } else {
                            $("#item-asker").remove();
                        }
                    }
                );
            }
        } else {
            window.location.replace("/ballot-list");
        }
    }
})(jQuery, window, document);
