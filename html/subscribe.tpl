{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">

    <div class="small-box small-box-transparent text-center">
        <div class="text-center mt-4 mb-4">
            <img class="logo-top-round" src="/images/svg/logo-bleu-civicpower.svg" alt="Logo Civicpower"/>
        </div>
    </div>
    <div class="small-box-extender">
        <div class="small-box" id="box-subscribe">
            <div class="valign-center mh-vh">
                <div class="mb-4 text-center cp_text_24_bold">
                    S'inscrire pour voter<br/>
                    c'est rapide et facile !
                </div>
                <div class="text-center cp_text_18">
                    Pour garantir la sécurité de votre compte<br/>nous avons besoin de vous envoyer<br/>un code de sécurité
                </div>
                <div class="mt-3 text-center">
                    <form method="post" id="form_subscribe">
                        <div id="div-outer-slide">
                            <div class="mb-0" id="div-mobile">
                                <label for="input_phone_mobile" class="mb-1 cp_text_18_bold">Votre numéro de mobile</label>
                                <div class="form-group mt-3 mb-4">
                                    <input type="tel" name="phone" id="input_phone_mobile" class="form-control cp_text_14" autofocus autocomplete="true"/>
                                </div>
                                <div class=" cpcolor-blue1"><label id="btn-change-email" class="link-change cp_text_14">Je m'inscris avec un email</label></div>
                            </div>
                            <div class="mb-0" id="div-email">
                                <label for="input_email" class="mb-1  cp_text_18_bold">Votre adresse email</label>
                                <div class="form-group mt-3 mb-4 div-input-email">
                                    <input type="email" name="email" id="input_email" class="form-control" autocomplete="true"/>
                                </div>
                                <div class=" cpcolor-blue1"><label id="btn-change-phone" class="link-change cp_text_14">Je m'inscris avec un numéro de téléphone</label></div>
                            </div>
                        </div>
                        <div class="text-left mw400 w-100">
                            <div class="custom-control custom-checkbox div_checkbox">
                                <input type="checkbox" id="cb_privacy" name="accept-privacy" class="custom-control-input"/>
                                <label for="cb_privacy" class="div_checkbox cp_text_13 mb-2 custom-control-label">
                                    En m'inscrivant, j'ai pris connaissance et j'accepte <a href="https://www.civicpower.io/donnees-personnelles-app">la politique de protection des données personnelles de Civicpower</a>
                                </label>
                            </div>
                            <div class="custom-control custom-checkbox div_checkbox">
                                <input type="checkbox" id="cb_charte" name="accept-charte" class="custom-control-input"/>
                                <label for="cb_charte" class="div_checkbox cp_text_13 mb-3 custom-control-label">
                                    Je m'engage à respecter <a target="_blank" href="https://www.civicpower.io/charte-app">la charte de bonne conduite</a>
                                </label>
                            </div>
                        </div>
                        <div class=" mb-3">
                            <button id="btn_send_code" type="submit" class="btn cp_button_red">Envoyer un code</button>
                        </div>
                        <div class="">
                            <a id="btn_connexion" class="btn cp_button_blue" href="/login">Connexion</a>
                        </div>
                        <div class="mt-2">
                            <a class="cp_text_13" href="/lost-password">Mot de passe oublié</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="small-box" id="box-confirm">
            <div class="text-center mt-4">
                Taper le code d'activation reçu par <span id="span_destination"></span>
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
                            <input size="6" maxlength="4" type="text" class="validation-code-input text-center form-control-lg" id="input_phone_code" autofocus="autofocus"/>
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
    {include file="block-social.tpl"}
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
                    Un utilisateur avec cette adresse email ou ce numéro de téléphone existe déjà. Merci de vous connecter.<br/>
                </p>
                <p><a class="btn btn-primary" href="/login">Se connecter</a></p>
                <p><a class="btn btn-secondary" href="/lost-password">Mot de passe perdu ?</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>