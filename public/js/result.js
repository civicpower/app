;(function ($, window, document, _undefined) {
    var page_ballot_token = 0;
    var page_shortcode = 0;
    $(document).ready(function () {
        page_shortcode = $("#shortcode").val();
        if (page_shortcode.length > 0) {
            civicpower_get_ballot_token(page_shortcode, function (jsone) {
                manage_ballot_token(jsone.data);
            });
        } else {
            manage_ballot_token($("#ballot_token").val());
        }

    });

    function manage_ballot_token(ballot_token) {
        $("#ballot_token").val(ballot_token);
        check_user_logged(function (user_token) {
            cp_load_user_steps(user_token);
            page_ballot_token = ballot_token;
            $(".ballot_token_param").attr("href","/"+String(page_ballot_token));
            get_ballot(page_ballot_token, user_token);
        });
    }

    function get_ballot(ballot_token, user_token) {
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
            window.location = "/404";
            return;
        }
        /****** CHECK ACTIVE *****/
        if (data.ballot_started == 0) {
            $(".div_countdown_outer").remove();
            $("#ballot-not-started").removeClass("d-none");
        } else {
            if (data.ballot_running == 1) {
                var deadline = Date.createFromMysql(data.ballot_end);
                //var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
                initializeClock('clockdiv', deadline);
            } else if (data.ballot_finished == 1) {
                $(".ballot_end").text(data.ballot_end_date_fr + " à " + data.ballot_end_hourmin_fr);
                $(".div_countdown_outer").remove();
                $("#ballot-ended").removeClass("d-none");
                $(".btn-retour-vote").remove();
            }
        }
        if ($(".ballot_end").text().trim().length == 0) {
            $(".ballot_end").remove();
        }
        /***** HEADER ****/
        $("#ballot_title").text(data.ballot_title);
        $("#ballot_description").text(data.ballot_description);
        $("#ballot_engagement").text(data.ballot_engagement);
        /***** QUESTIONS ****/
        var question_is_qcm=false,  o = null, q = null, $question_item = null, $option_item = null, question_id = null, nb_participation_total = 0;
        var hide_results = false;
        for (var i in data.question_list) {
            q = data.question_list[i];
            question_id = q.question_id;
            $question_item = $("#hidden-stock .question_item").clone();
            $question_item.find(".question_title").text(q.question_title);
            $question_item.find(".question_description").text(q.question_description);
            question_is_qcm = false;
            if(q.question_nb_vote_min>1 || q.question_nb_vote_max>1){
                question_is_qcm = true;
                var $div_qcm = $question_item.find(".question_qcm,.question_qcm_note");
                $div_qcm.removeClass("d-none");
                $div_qcm.find(".nb-choice").text(q.question_nb_vote_max);
                if(q.question_nb_vote_min == q.question_nb_vote_max){
                    $div_qcm.find(".option-strict").removeClass("d-none");
                    $div_qcm.find(".option-max").removeClass("d-none");
                }else{
                    $div_qcm.find(".option-until").removeClass("d-none");
                }
            }
            $question_item.attr("data-nb_vote_min", q.question_nb_vote_min);
            $question_item.attr("data-nb_vote_max", q.question_nb_vote_max);
            if (q.question_nb_vote && !isNaN(q.question_nb_vote) && q.question_nb_vote > nb_participation_total) {
                nb_participation_total = q.question_nb_vote;
            }
            var nb_vote = 0;
            for (var j in q.option_list) {
                nb_vote += parseInt(q.option_list[j].option_nb_vote);
            }
            for (var j in q.option_list) {
                o = q.option_list[j];
                $option_item = $("#hidden-stock .option_item").clone();
                let str_voted = "";
                if(o.option_user_has_voted == 1){
                    str_voted = '<span class="float-right badge badge-success">Votre choix</span>';
                }
                $option_item.find(".option_title").html(o.option_title+str_voted);
                $option_item.find(".option_description").text(o.option_description);
                let prc_vote_init = nb_vote==0?0:parseFloat((o.option_nb_vote * 100)/nb_vote);
                let prc_vote = String(Math.floor(prc_vote_init*1000)/1000);
                $option_item.find(".div_value_span").html(String(prc_vote.replace(/(\.\d\d)(\d+)/,"$1<small class='d-none text-decimal'>$2</small>"))+"%");
                $option_item.find(".progline").css("background-size",String(prc_vote_init)+"%");
                $option_item.find(".div_value_span2").html(String(o.option_nb_vote)+"");
                if (o.option_nb_vote == "?") {
                    hide_results = true;
                }
                $option_item.appendTo($question_item.find(".option_list"));
            }
            $question_item.appendTo("#question_list");
        }
        if (hide_results) {
            $("#ballot-wait-end").removeClass("d-none");
        }
        $("#nb_participation_total,.nb_participation_total").text(nb_participation_total);
        calculate_choice_nb();
    }
    function calculate_choice_nb(){
        $(".question_item").each(function(){
            var $question_div = $(this);
            var nb_prticipation = parseInt($("#nb_participation_total").text().trim() || 0);
            var nb_choice_max = parseInt($question_div.find(".question_qcm_note .nb-choice").text() || 0);
            var res = nb_prticipation * nb_choice_max;
            console.log(nb_prticipation,nb_choice_max,res);
            $question_div.find(".question_qcm_note .choice_nb").text(res);
        });
    }
})(jQuery, window, document);
