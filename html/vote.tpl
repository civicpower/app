{seo_title}{if isset($seo_title) && strlen($seo_title)>0}{$seo_title} - Civicpower{else}Application Civicpower{/if}{/seo_title}
{seo_description}{if isset($seo_description) && strlen($seo_description)>0}{$seo_description}{else}{$seo_title}{/if}{/seo_description}

<input type="hidden" id="shortcode" value="{$shortcode|for_input}"/>
<input type="hidden" id="ballot_token" value="{$ballot_token|for_input}"/>

<div id="init_hidden" class="d-none">
    {include file="block-menu-user.tpl" }
    <div class="valign-center mh-vh">
        {include file="block-countdown2.tpl"}
        <div id="div-create-account" class="text-center mt-2 mb- alert alert-primary" style="display: none">
            <div>
                Pour pouvoir participer à cette consultation, vous devez créer un compte<br />ou vous connecter à votre compte civicpower
            </div>
            <div class="mt-2">
                <a href="/subscribe" class="btn btn-primary btn-lg">Je m'inscris</a>
                <a href="/login" class="btn btn-primary btn-lg">Je me connecte</a>
            </div>
        </div>
        <div id="div-account-created" style="display:none;">
            <div class="text-center emptiable">
                <img class="avatar-asker" data-bo="https://{$smarty.env.BO_URL}/uploads/pp/" src="https://{$smarty.env.BO_URL}/uploads/pp/default.png"/>
                <div class="cp_text_14_bold" id="avatar-asker-name"></div>
            </div>
            <div class="small-box emptiable px-0">
                <div id="ballot-not-started" class="mx-5 alert alert-warning text-center font-weight-bold d-none">Cette consultation n'est pas encore ouverte</div>
                <div id="ballot-ended" class="mx-5 alert alert-warning text-center font-weight-bold d-none">Cette consultation est désormais close</div>
                <div id="ballot-cannot-vote" class="mx-5 alert alert-warning text-center font-weight-bold d-none">Vous ne pouvez pas voter pour cette consultation</div>


                <h1 id="ballot_title" class="mt-1 px-5 cp_text_14_bold_maj text-center"></h1>
                <div id="ballot_description" class="mt-3  px-5 text-center cp_text_14"></div>
                <div class="text-center mt-2">Engagement :</div>
                <div id="ballot_engagement" class="mt-0 px-5 text-center cp_text_14"></div>
            </div>
            <div class="small-box emptiable px-0">
                <form id="form_ballot">
                    <div id="msg_already_voted" class="text-center alert alert-warning mt-4"></div>
                    <div id="question_list"></div>
                </form>
                <div class="mt-4 text-center">
                    <button id="btn-confirm-vote" class="btn cp_button_red" {*disabled="disabled"*}>Voter</button>
                </div>
                <div class="mt-4 text-center" id="div_see_results_live">
                    <a class="ballot_token_param btn-retour-vote btn btn-light btn-civicpower">Voir la tendance des votes</a>
                </div>
            </div>
            <div class="text-center small-box cpcolor-blue3 box-404" style="display: none;">
                <img src="/images/svg/404.svg" class="img-icon-cp mt-5 mb-2"/>
                <h2 class="font-weight-bold mt-2 mb-2">Oups!</h2>
                <div class="mb-5 mt-2">Cette consultation n'a pas été trouvée.</div>
                <div>
                    <a class="font-weight-bold btn btn-danger btn-danger2" href="/ballot-list">Retrouvez d'autres consultations ici</a>
                </div>
            </div>
        </div>
    </div>

</div>
{include file="block-menu-user-bottom.tpl" }

<div id="hidden-stock" class="d-none">
    <div class="question_item mt-1 text-center">
        <div class="px-4 question_title cp_text_16_bold">question_title</div>
        <div class="question_description mt-1  px-5 text-center cp_text_14">question_description</div>
        <div class="question_qcm d-none alert alert-primary">
            Vote à préférences multiples
            <span class="option-strict d-none">strict</span>
            <span class="option-max d-none">max</span> :
            <i>Choisir <span class="option-until d-none">jusqu'à</span> <span class="nb-choice">0</span> options</i>
        </div>
        <div class="question_error alert alert-warning">
            Votre réponse comporte des erreurs,<br>
            veuillez vérifier le nombre maximum de réponses prévues.
        </div>
        <div class="option_list btn-group-toggle btn-group-lg btn-block" data-toggle="buttons"></div>
    </div>

    <label class="option_item btn-option-vote btn btn-block">
        <input class="input_option" type="radio" autocomplete="off">
        <label class="option_title cp_text_14_bold">option_title</label>
    </label>
</div>

