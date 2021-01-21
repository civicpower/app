{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">

    <div class="small-box-extender">
        {include file="block-menu-user.tpl" }
        <div id="box-subscribe">
            <div class="valign-center mh-vh">
                <div class="small-box">
                    {include file="block-user-steps.tpl"}
                    <div class="mb-4 cp_text_14_bold mx-auto  text-center">
                        Renseignez votre adresse email
                    </div>
                    <form method="post" id="form_subscribe" class="mw500 w-100 mt-3">
                        <div id="div-outer-slide">
                            <div class="text-center mt-3 mb-3" id="div-email">
                                {*                            <label for="input_email" class="civicpower-title1 mb-1 text-center cpcolor-blue1">Votre adresse email</label>*}
                                <div class="form-group mt-3 mb-4">
                                    <input type="email" name="email" id="input_email" class="form-control font-weight-bold cp_text_14" autofocus autocomplete="true"/>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <button id="btn_send_code" type="submit" class="btn cp_button_red">Envoyer un code</button>
                        </div>
                        {include file="block-btn-passer.tpl"}
                    </form>
                </div>
            </div>
        </div>

        <div class="small-box" id="box-confirm">
            <div class="valign-center mh-vh">
                <div class="text-center mt-4">
                    Taper le code d'activation reçu par email
                </div>
                <div class="text-center mb-4">
                    <strong class="font-weight-bold" id="span_phone_number"></strong>
                </div>
                <div>
                    <form id="form_phone_activation" method="post">
                        <div class="form-group">
                            <div class="text-center">
                                <span id="span_code_prefix">CP-</span>
                                <input size="6" maxlength="4" type="text" class="validation-code-input text-center form-control-lg" id="input_phone_code" autofocus="autofocus"/>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn cp_button_red">Valider</button>
                        </div>
                        <div class="mb-4 mt-4 text-center">
                            <button id="btn_reset" type="button" href="" class="btn btn-sm">
                                Vous n'avez rien reçu ?<br/>
                                <u>Renvoyer le code</u>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }

<div class="modal fade" id="modal_user_exists" tabindex="-1" aria-labelledby="modal_user_exists_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_user_exists_label">Cet utilisateur existe déjà</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>
                    Un utilisateur avec ce numéro de téléphone existe déjà. Merci d'en choisir un autre.<br/>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

