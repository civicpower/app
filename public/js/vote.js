;(function ($, window, document, undefined) {
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
        check_user_logged(
            function (user_token, data) {
                cp_load_user_steps(user_token);
                cp_dispatch(user_token, [
                    "city",
                ], "");
                invoke_get_ballot(user_token);
                bind_check_ballot();
                bind_btn_confirm_vote();
                $("#div-account-created").show();
            },
            function () {
                invoke_get_ballot(null, function () {
                    cp_hide_logged_menu();
                    $("#div-create-account").slideDown("slow");
                });
            }
        );
    }





    function invoke_get_ballot(user_token, func) {
        page_ballot_token = $("#ballot_token").val();
        project_manage_ballot_token(page_ballot_token);
        $(".ballot_token_param").attr("href","/r/"+String(page_ballot_token));
        get_ballot(page_ballot_token, user_token, func);
    }

    function bind_btn_confirm_vote() {
        $("#btn-confirm-vote").on("click", function () {
            if (all_questions_are_voted()) {
                save_votes();
            } else {
                cp_alert("Merci de voter toutes les questions");
            }
        });
    }

    function save_votes() {
        var data = [];
        $("#question_list .question_item .input_option:checked").each(function () {
            var $this = $(this);
            data.push($this.val());
        });
        data = data.join(",");
        api_call(
            "vote",
            "post",
            {
                token: civicpower_user_token(),
                option_list: data
            },
            function (jsone) {
                if (jsone.code === "vote_recorded") {
                    // alert("ok");
                    window.location = "/vote-confirmation?ballot_token=" + String(encodeURIComponent(page_ballot_token));
                } else {
                    if (typeof jsone.message == "string" && String(jsone.message).length > 0) {
                        cp_alert(jsone.message);
                    } else {
                        cp_alert("Une erreur est survenue !");
                    }
                }
            }
        )
    }

    function all_questions_are_voted() {
        var all_voted = true;
        $("#question_list .question_item").each(function () {
            var $question_item = $(this);
            var q_good = true;
            var nb_vote_min = $question_item.attr("data-nb_vote_min");
            var nb_vote_max = $question_item.attr("data-nb_vote_max");
            var nb_checked = $question_item.find(".input_option").filter(":checked").length;
            if (!(nb_checked >= nb_vote_min && nb_checked <= nb_vote_max)) {
                if(nb_checked==1 && $question_item.find(".input_option").filter(":checked").first().attr("data-option_can_be_deleted")==0) {

                }else {
                    q_good = false;
                    all_voted = false;
                }
            } else {
            }
            if(!q_good && nb_checked>1){
                $question_item.addClass("has_error");
            }else {
                $question_item.removeClass("has_error");
            }
        });
        return all_voted;
    }

    function bind_check_ballot() {
        $(document).on("change", ".input_option", function () {
            check_option($(this));
            check_ballot();
        });
    }

    function check_option($option) {
        var can_be_deleted = $option.attr("data-option_can_be_deleted") == 1;
        var invert = can_be_deleted ? 0 : 1;
        $option
            .closest(".option_list")
            .find(can_be_deleted==0?".input_option[id!='"+String($option.attr("id"))+"']":"[data-option_can_be_deleted='"+String(invert)+"']")
            .prop("checked",false)
            .closest(".option_item")
            .removeClass("active");

    }
    function check_ballot() {
        var $btn_confirm = $("#btn-confirm-vote");
        if ($btn_confirm.hasClass("user_cannot_vote")) {
            $btn_confirm.prop("disabled", false);
            $btn_confirm.hide();
        } else {
            if (all_questions_are_voted()) {
                $btn_confirm.prop("disabled", false);
            } else {
                $btn_confirm.prop("disabled", true);
            }
        }
    }

    function get_ballot(ballot_token, user_token, func) {
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
                check_ballot();
                if (typeof func != 'undefined' && func != null) {
                    civicpower_mainfunc(func, user_token, jsone);
                }
            }
        );
    }

    function write_ballot(data) {
        if (data.length <= 0 || typeof data.ballot_id === 'undefined') {
            $("#init_hidden .emptiable").empty().remove();
            $(".box-404").show();
            $(".div_countdown_outer").append('<div class="text-center civicpower-title1">Cette consultation est introuvable !</div>');
            return;
        }
        var user_can_vote = true;
        if (data.ballot_user_has_voted == 1 && data.ballot_can_change_vote == 0) {
            $("#msg_already_voted").text("Vous avez déjà voté ! Cette consultation ne permet pas de modifier votre vote.");
            user_can_vote = false;
        } else {
            $("#msg_already_voted").remove();
        }
        if (!user_can_vote) {
            $("#form_ballot").addClass("user_cannot_vote");
            $("#btn-confirm-vote").remove();
        }
        /****** CHECK ACTIVE *****/
        if (data.ballot_started == 0) {
            $(".div_countdown_outer").remove();
            $("#ballot-not-started").removeClass("d-none");
            $("#btn-confirm-vote").remove();
            $(".option_item").addClass("disabled");
        } else {
            if (data.ballot_running == 1) {
                var deadline = Date.createFromMysql(data.ballot_end);
                //var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
                initializeClock('clockdiv', deadline);
            } else if (data.ballot_finished == 1) {
                $(".div_countdown_outer").remove();
                $("#ballot-ended").removeClass("d-none");
                $("#btn-confirm-vote").remove();
                $(".option_item").addClass("disabled");
            }
        }
        if (data.can_vote === 0) {
            $("#ballot-cannot-vote").removeClass("d-none");
            $("#btn-confirm-vote").remove();
            $(".option_item").addClass("disabled");
        }
        /*************** AVATAR ASKER *********/
        var $avatar = $(".avatar-asker");
        $(".avatar-asker").attr("src", $avatar.attr("data-bo") + String(data.asker_token) + ".png?rand="+String(Math.random()*9999)+"");
        $("#avatar-asker-name").text(String(data.asker_name));
        /***** CAN SEE RESULTS LIVE ****/
        if (data.ballot_see_results_live == "0") {
            $("#div_see_results_live").remove();
        }
        /***** HEADER ****/
        $("#ballot_title").text(data.ballot_title);
        $("#ballot_description").text(data.ballot_description);
        $("#ballot_engagement").text(data.ballot_engagement);
        /***** QUESTIONS ****/
        var question_is_qcm=false, $option_elm = null, o = null, q = null, $question_item = null, $option_item = null, question_id = null, nb_participation_total = 0;
        for (var i in data.question_list) {
            q = data.question_list[i];
            question_id = q.question_id;
            $question_item = $("#hidden-stock .question_item").clone();
            $question_item.find(".question_title").text(q.question_title);
            $question_item.find(".question_description").text(q.question_description);
            question_is_qcm = false;
            if(q.question_nb_vote_min>1 || q.question_nb_vote_max>1){
                question_is_qcm = true;
                var $div_qcm = $question_item.find(".question_qcm");
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
            for (var j in q.option_list) {
                o = q.option_list[j];
                $option_item = $("#hidden-stock .option_item").clone();
                if(question_is_qcm){
                    $option_item.find(".input_option").attr("type","checkbox");
                }
                $option_item.attr("for","option_"+String(o.option_id));
                $option_item.find(".input_option").attr("id","option_"+String(o.option_id));
                $option_item.find(".option_title").attr("for","option_"+String(o.option_id));
                $option_item.find(".input_option").attr("data-option_can_be_deleted",o.option_can_be_deleted);
                $option_item.find(".option_title").text(o.option_title);
                $option_item.find(".option_description").text(o.option_description);
                $option_elm = $option_item.find(".input_option");
                $option_elm.attr("name", "question[" + String(question_id) + "]");
                $option_elm.attr("value", o.option_id);
                if (o.option_user_has_voted == 1) {
                    $option_item.addClass("active");
                    $option_elm.prop("checked", true);
                }
                if (!user_can_vote) {
                    $option_item.addClass("disabled");
                    $option_elm.prop("disabled", true);
                }
                $option_item.appendTo($question_item.find(".option_list"));
            }

            $question_item.appendTo("#question_list");
        }
        $("#question_list .option_list").each(function () {
            var $this = $(this);
            var nb = $this.find(".option_item").length;
            var prc = 100 / nb * 0.99;
            var text = $this.text().trim().replace(/\s+/g, " ").trim();
            var len = text.length;
            if (len <= 50 && nb <= 3) {
                $this.addClass("nb_option_" + String(nb));
                $this.addClass("nb_option_" + String(nb));
                $this.removeClass("btn-block");
                $this.find(".option_item").removeClass("btn-block");
                $this.find(".option_item").addClass("option-horizontal");
                $this.find(".option_item").css("width", String(prc) + "%");
            }
        });
        $("#nb_participation_total").text(nb_participation_total);
    }
})(jQuery, window, document);
