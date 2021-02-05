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
                <div class="mb-4 cp_text_14_bold mx-auto  text-center">Connexion à Civicpower</div>
                <div class="mt-3 w-100 mw500">
                    <form action="#" method="post" id="form_login">
                        <div id="error_div_outer">
                            <div id="error_div" class="alert alert-warning py-1 text-center"></div>
                        </div>
                        <div id="div-outer-slide">
                            <div class="form-label-group mw90 mx-auto">
                                <button type="button" class="btn btn-primary btn-eye" id="btn-continue">Continuer</button>
                                <input autocomplete="phone email" type="text" id="input_username" class="form-control" placeholder="Adresse e-mail ou numéro de mobile" required="required"/>
                                <label for="input_username">Adresse e-mail ou mobile</label>
                            </div>
                            <div class="form-label-group mw90 mx-auto" id="div-password">
                                <div id="div-password-inner">
                                    <input autocomplete="current-password" type="password" id="input_password" class="form-control" placeholder="Mot de passe" required="required"/>
                                    <label for="input_password">Mot de passe</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3" id="div-btn-connect">
                            <div id="div-btn-connect-inner">
                                <button type="button" id="btn_send_code" type="submit" class="btn cp_button_red">Connexion</button>
                            </div>
                        </div>
                        <div class="text-center"><a href="/lost-password" class="cp_text_14">Mot de passe perdu</a></div>
                        <div class="text-center"><a href="/subscribe" class="cp_text_14">Créer un compte</a></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="small-box" id="box-confirm">
            <div class="text-center mt-4">
                Taper le code d'activation reçu
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

            {include file="block-social.tpl"}


    </div>

</div>
