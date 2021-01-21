{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}


<div id="init_hidden" class="d-none">
{include file="block-menu-user.tpl" }

    <div class="small-box">
        {include file="block-user-steps.tpl"}
        <div class="text-center mb-2 civicpower-title1 cpcolor-blue2">Dernière étape pour valider votre compte Civicpower</div>
        <div class="text-center mb-4 civicpower-title1 font-weight-normal cpcolor-blue2">Complétez votre adresse postale</div>
        <form id="form_user_edit" method="post">
            <div class="row mw60 mx-auto">
                <div class="col-xl-3">
                    <div class="form-label-group">
                        <input type="text" id="input_streetnum" class="form-control" placeholder="N°" required="required" autofocus="autofocus"/>
                        <label for="input_streetnum">N°</label>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="form-label-group">
                        <input type="text" id="input_street" class="form-control" placeholder="Rue, lieu dit, ..." required="required"/>
                        <label for="input_street">Rue, lieu dit, ...</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-label-group">
                        <input type="number" id="input_zipcode" class="form-control" placeholder="Code postal" required="required"/>
                        <label for="input_zipcode">Code postal</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group" id="div_city_id">
                        <label id="label_city" for="select_city_id">Commune</label>
                        <select id="select_city_id" class="form-control">
                            <option>Sélectionnez votre commune</option>
                        </select>
                    </div>
                    <div id="error_div" class="alert alert-danger">Le code postal renseigné est inconnu</div>
                </div>
                <div class="text-center col-12">
                    <button disabled="disabled" id="btn_validate" type="submit" class="btn cp_button_red">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }