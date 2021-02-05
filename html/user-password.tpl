{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">

    <div class="small-box-extender">
        {include file="block-menu-user.tpl" }
        <div class="small-box" id="box-subscribe">
            {include file="block-user-steps.tpl"}
            <div class="valign-center mh-vh">
                <div class="mb-4 cp_text_14_bold mx-auto  text-center">
                    Sécurisons votre compte<br/>
                    par un mot de passe
                </div>
                <div class="mb-4 cp_text_13 mx-auto  text-center">
                    Utilisez au moins huit caractères avec des lettres, des chiffres et des symboles
                </div>
                <form method="post" id="form_password">
                    <div class="mt-3 row">
                        <div class="form-label-group col-12 div-password">
                            <button tabindex="-1" type="button" class="btn btn-light btn-eye"></button>
                            <input type="password" id="input_password" class="input-pass text-center form-control" placeholder="Mot de passe" required="required" autofocus="autofocus" />
                            <label for="input_password" class="text-center">Mot de passe</label>
                        </div>
                        <div class="form-label-group col-12 div-password">
                            <button tabindex="-1" type="button" class="btn btn-light btn-eye"></button>
                            <input type="password" id="input_password2" class="input-pass text-center form-control" placeholder="Répétez le Mot de passe" required="required" autofocus="autofocus" />
                            <label for="input_password2" class="text-center">Répétez le Mot de passe</label>
                        </div>
                        <div id="div-error-outer" class="col-12">
                            <div id="div-error" class="py-1 alert-warning alert text-center"></div>
                        </div>
                        <div class="text-center mb-3 col-12">
                            <button id="btn_validate" type="submit" class="btn cp_button_red">Valider</button>
                        </div>
                        <div class="text-center col-12">
                            {include file="block-btn-passer.tpl"}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
