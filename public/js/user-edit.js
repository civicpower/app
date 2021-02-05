;(function ($, window, document, _undefined) {
    $(document).ready(function () {
        first_focus();
        check_user_logged(
            function (user_token, jsone) {
                cp_load_user_steps(user_token);
                fill_form(jsone.data);
                bind_form_user_edit_submit(user_token);
            },
            null,
            "get_user_info"
        );
    });

    function first_focus() {
        setTimeout(function(){
            $("#input_firstname").focus();
        },400);
    }


    function fill_form(data) {
        $("#input_firstname").val(data.user_firstname);
        $("#input_lastname").val(data.user_lastname);
        $("#input_email").val(data.user_email);
        $("#input_mobile").val(data.user_phone_national);
        $("#input_city").val(data.nom_commune);
        $("#input_zipcode").val(data.code_postal);
        $("#input_password").val(data.user_password.length>0?"***********":"");
        first_focus();
        if($(".form-label-group .btn_modify").length>0) {
            $(".form-label-group").each(function () {
                var $div = $(this);
                if($div.find(".btn_modify").length<=0){
                    return;
                }
                var value = $div.find("input").val();
                if(value.trim().length == 0){
                    $div.find(".btn_modify").text("Ajouter");
                }
            });
        }
    }

    function bind_form_user_edit_submit(user_token) {
        $("#form_user_edit").on("submit", function (e) {
            var error = [];
            var params = {
                token: user_token
            };

            var firstname = $("#input_firstname").val().trim();
            var lastname = $("#input_lastname").val().trim();


            if (firstname.length < 2 || firstname.length > 50 || firstname.match(/\d/g)) {
                error.push("Le prénom est incorrect !");
            } else {
                params.firstname = firstname;
            }
            if (lastname.length < 2  || lastname.length > 50 || lastname.match(/\d/g)) {
                error.push("Le nom est incorrect !");
            } else {
                params.lastname = lastname;
            }

            if (error.length > 0) {
                cp_alert(error.join("\n"));
            } else {
                api_call(
                    "update_user",
                    "post",
                    params,
                    function (jsone) {
                        if (jsone.status == "success") {
                            window.location.replace("/settings");
                        } else {
                            if (jsone.message != null && jsone.message.length > 0) {
                                cp_alert(jsone.message);
                            } else {
                                cp_alert("Une erreur est survenue !");
                            }
                        }
                    }
                );
            }
            e.preventDefault();
            return false;
        });
    }

})(jQuery, window, document);
