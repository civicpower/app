{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">

    <div class="small-box small-box-transparent text-center">
        <div class="text-center mt-4 mb-4">
            <img class="logo-top-round" src="/images/svg/logo-bleu-civicpower.svg" alt="Logo Civicpower"/>
        </div>
    </div>
    <div class="small-box" id="box-subscribe">
        <div class="valign-center mh-vh">
            <div class="mb-4 cp_text_14_bold mx-auto  text-center">
                Mot de passe perdu
            </div>
            <div class="cp_text_14 mw90 mx-auto  text-center">
                Votre mot de passe est indispensable pour accéder au service.<br/>
                Si vous l'avez oublié ou si vous ne le connaissez pas, voici comment le réinitialiser :<br/>
                Entrez ci-dessous le numero de mobile OU le mail avec lequel vous vous êtes inscrit la premiere fois
            </div>
            <hr/>
            <div class="mt-3 w-100 mw500">
                <form action="#" method="post" id="form_lost">
                    <div id="div-outer-slide">
                        <div class="form-label-group mw90 mx-auto">
                            <input autocomplete="phone email" type="text" id="input_username" class="form-control text-center" placeholder="Adresse e-mail ou numéro de mobile" required="required"/>
                            <label for="input_username" class="text-center">Adresse e-mail ou numéro de mobile</label>
                        </div>
                    </div>
                    <div id="error_div_outer">
                        <div id="error_div" class="alert alert-danger py-1 text-center"></div>
                    </div>
                    <div class="text-center mb-3">
                        <button id="btn_send_code" type="submit" class="btn cp_button_red">Continuer</button>
                    </div>
                    <div class="text-center"><a href="/login" class="cp_text_14">Connexion</a></div>
                    <div class="text-center"><a href="/subscribe" class="cp_text_14">Créer un compte</a></div>
                </form>
            </div>
        </div>
    </div>

    <div class="small-box" id="box-confirm">
        <div class="text-center mt-4">
            Taper le code d'activation reçu
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

<div class="modal fade" id="modal_user_does_not_exist" tabindex="-1" aria-labelledby="modal_user_exists_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_user_exists_label">Cet utilisateur n'existe pas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>
                    Aucun utilisateur n'a été trouvé avec cette adresse email ou ce numéro de téléphone. Merci de vous inscrire.<br/>
                </p>
                <p><a class="btn btn-primary" href="/subscribe">Je m'inscris</a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
