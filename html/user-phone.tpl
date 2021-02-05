{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">

    <div class="small-box-extender">
        {include file="block-menu-user.tpl" }
        <div class="small-box" id="box-subscribe">
            <div class=" valign-center mh-vh">
                {include file="block-user-steps.tpl"}
                <div class="mt-3">
                    <form method="post" id="form_subscribe">
                        <div id="div-outer-slide">
                            <div class="text-center mb-3" id="div-mobile">
                                <label for="input_phone_mobile" class="mb-4 text-center cp_text_14_bold">Renseignez votre numéro de mobile</label>
                                <div class="form-group mt-3 mb-4">
                                    <input type="tel" name="phone" id="input_phone_mobile" class="form-control cp_text_14" autofocus autocomplete="true" />
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <button id="btn_send_code" type="submit" class="btn cp_button_red">Envoyer un code</button>
                        </div>
                    </form>
                </div>
            </div>
            {include file="block-btn-passer.tpl"}
        </div>

        <div class="small-box" id="box-confirm">
            <div class="text-center mt-4">
                Nous avons besoin de vous envoyer un code pour vérifier que c'est bien votre numéro de mobile
            </div>
            <div class="text-center mt-4">
                Taper le code d'activation reçu par sms au
            </div>
            <div class="text-center mb-4">
                <strong class="font-weight-bold" id="span_phone_number"></strong>
                <input type="hidden" id="input_phone_prefix"/>
                <input type="hidden" id="input_phone"/>
                <input type="hidden" id="input_phone_international"/>
            </div>
            <div>
                <form id="form_phone_activation" method="post">
                    <div class="form-group">
                        <div class="text-center">
                            <span id="span_code_prefix">CP-</span>
                            <input size="6" maxlength="4" type="text" class="text-center form-control-lg" id="input_phone_code" autofocus="autofocus"/>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn cp_button_red">Valider</button>
                    </div>
                    <div class="mb-4 mt-4 text-center">
                        {include file="block-renvoyer-code.tpl"}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


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

