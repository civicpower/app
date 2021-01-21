{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">

    <div class="small-box-extender">
        {include file="block-menu-user.tpl" }
        <div class="small-box" id="box-subscribe">
            {include file="block-user-steps.tpl"}
            <div  class="valign-center mh-vh">
                <div class="mt-3  w-100 mw500 mh-vh valign-center">
                    <form method="post" id="form_location">
                        <div class="mb-4 mx-auto  text-center cp_text_14_bold">
                            Participez aux consultations de votre commune,<br />renseignez votre code postal
                        </div>
                        <div class="form-label-group">
                            <input type="tel" maxlength="5" id="input_zipcode" class="text-center form-control" placeholder="Code postal" required="required" autofocus="autofocus"/>
                            <label for="input_zipcode" class="text-center">Code postal</label>
                        </div>
                        <div class="text-center mt-3 mb-3">
                            <select id="select_city_id" class="form-control">
                                <option value="">Renseignez votre code postal ...</option>
                            </select>
                            <div id="error_div" class="alert alert-danger">Le code postal renseigné est inconnu</div>
                        </div>
                        <div class="text-center mb-3">
                            <button disabled="disabled" id="btn_validate" type="submit" class="btn cp_button_red">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }