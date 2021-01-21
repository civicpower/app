{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}


<div id="init_hidden" class="d-none">
    {include file="block-menu-user.tpl" }

    <div class="small-box">

        <div class="mb-4">
            <div class="mb-4 mw500 mx-auto">
                <h2 class="cp_text_14_bold text-center ">Mon compte</h2>
                <div class="text-center mb-3 mw80 mx-auto cp_text_14">
                    Civicpower protège la confidentialité de vos données.<br/>Les informations que vous nous transmettez permettent de mieux adresser les consultations.
                </div>
                <h2 class="text-center cp_text_14_bold ">Modifier vos informations personnelles</h2>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_firstname" class="form-control cp_text_13" placeholder="Prénom" required="required"/>
                    <label for="input_firstname" class="cp_text_10">Prénom</label>
                    <a class="cp_link_13 btn_modify"  href="/user-edit">Modifier</a>
                </div>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_lastname" class="form-control cp_text_13" placeholder="Nom" required="required" autofocus="autofocus"/>
                    <label for="input_lastname" class="cp_text_10">Nom</label>
                    <a class="cp_link_13 btn_modify" href="/user-edit">Modifier</a>
                </div>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_mobile" class="form-control cp_text_13" placeholder="Mobile" required="required" autofocus="autofocus"/>
                    <label for="input_mobile" class="cp_text_10">Mobile</label>
                    <a class="cp_link_13 btn_modify" href="/user-phone">Modifier</a>
                </div>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_email" class="form-control cp_text_13" placeholder="Email" required="required" autofocus="autofocus"/>
                    <label for="input_email" class="cp_text_10">Email</label>
                    <a class="cp_link_13 btn_modify" href="/user-email">Modifier</a>
                </div>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_city" class="form-control cp_text_13" placeholder="Commune" required="required" autofocus="autofocus"/>
                    <label for="input_city" class="cp_text_10">Commune</label>
                    <a class="cp_link_13 btn_modify" href="/user-location">Modifier</a>
                </div>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_zipcode" class="form-control cp_text_13" placeholder="Code postal" required="required" autofocus="autofocus"/>
                    <label for="input_zipcode" class="cp_text_10">Code postal</label>
                    <a class="cp_link_13 btn_modify" href="/user-location">Modifier</a>
                </div>

                <div class="form-label-group  mx-auto">
                    <input disabled="disabled" type="text" id="input_password" class="form-control cp_text_13" placeholder="Mot de passe" required="required" autofocus="autofocus"/>
                    <label for="input_password" class="cp_text_10">Mot de passe</label>
                    <a class="cp_link_13 btn_modify" href="/user-password">Modifier</a>
                </div>

                <div class="form-label form-label-membership p-3 px-3 mx-auto d-none">
                    <input id="cb_membership" type="checkbox" />
                    <label class="cp_text_13" for="cb_membership">
                        <span>J'accepte d'être informé par email de l'actualité et des nouveautés de Civicpower</span>
                    </label>
                </div>


            </div>
        </div>

    </div>
</div>
{include file="block-menu-user-bottom.tpl" }
