{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}

<div id="init_hidden" class="d-none">
    {include file="block-menu-user.tpl" }

    <div class="small-box">
        {include file="block-user-steps.tpl"}
        <div class="mh-vh valign-center">
            <form id="form_user_edit" class="mw500">
                <div class="text-center mb-2 cp_text_14_bold">Encore quelques instants</div>
                <div class="text-center mb-4 cp_text_14_bold">pour finaliser votre inscription</div>
                <div class="text-center mt-4 mb-4 cp_text_16_bold">Précisez votre prénom et nom</div>
                <div class="form-label-group mw90 mx-auto">
                    <input type="text" id="input_firstname" class="form-control" placeholder="Prénom" required="required"/>
                    <label for="input_firstname" class="">Prénom</label>
                </div>
                <div class="form-label-group mw90 mx-auto">
                    <input type="text" id="input_lastname" class="form-control" placeholder="Nom" required="required" autofocus="autofocus"/>
                    <label for="input_lastname" class="">Nom</label>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn cp_button_red">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }