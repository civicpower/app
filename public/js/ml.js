;(function ($, window, document, undefined) {
    $(document).ready(function () {
        bind_route();
    });
    function bind_route(){
        var shortcode = $("#shortcode").val();
        if(shortcode.length<=0){
            window.location.replace("/ballot-list");
        }else{
            api_call(
                "get_ml_route",
                "post",
                {
                    shortcode: shortcode
                },
                function(jsone){
                    if(typeof jsone.data.to == "string"){
                        civicpower_set_cookie("ml",jsone.data.to,2);
                    }
                    if(typeof jsone.data.ballot_token != "undefined"){
                        civicpower_set_cookie("ml_ballot_token",jsone.data.ballot_token,2);
                    }
                    if(typeof jsone.data.route == "string"){
                        window.location.replace(jsone.data.route);
                    }else{
                        window.location.replace("/ballot-list");
                    }
                }
            );
        }
    }
})(jQuery, window, document);
