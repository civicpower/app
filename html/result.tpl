{seo_title}{if isset($seo_title) && strlen($seo_title)>0}{$seo_title} - Civicpower{else}Application Civicpower{/if}{/seo_title}
{seo_description}{if isset($seo_description) && strlen($seo_description)>0}{$seo_description}{else}{$seo_title}{/if}{/seo_description}

<input type="hidden" id="shortcode" value="{$shortcode|for_input}"/>
<input type="hidden" id="ballot_token" value="{$ballot_token|for_input}"/>

<div id="init_hidden" class="d-none">
    {include file="block-menu-user.tpl" }
    {include file="block-countdown2.tpl"}
    <div class="small-box px-0">
        <div id="ballot-not-started" class="mx-5 alert alert-warning text-center font-weight-bold d-none">Cette consultation n'est pas encore ouverte</div>
        <div id="ballot-ended" class="mx-5 alert alert-warning text-center font-weight-bold d-none">Cette consultation est désormais close</div>
        <div id="ballot-wait-end" class="mx-5 alert alert-warning text-center font-weight-bold d-none">Veuillez attendre la fin de la consultation pour voir les résultats</div>
        <div class="mt-4 text-center ballot_end font-weight-bold"></div>
        <div class="text-center mt-2">Résultat pour :</div>
        <h1 id="ballot_title" class="mt-1 px-5 cp_text_14_bold_maj text-center"></h1>
        <div id="ballot_description" class="mt-3  px-5 text-center cp_text_14"></div>
        <div class="text-center mt-2">Engagement :</div>
        <div id="ballot_engagement" class="mt-0 px-5 text-center cp_text_14"></div>
        <div id="question_list" class="">

        </div>
        <div class="mt-4 text-center">
            <a class="ballot_token_param btn-retour-vote btn btn-light btn-civicpower">Retour au vote</a>
        </div>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }


<div id="hidden-stock" class="d-none">
    <div class="question_item mt-4">
        <div class="question_title px-5 text-center cp_text_16_bold">question_title</div>
        <div class="question_description px-5 text-center cp_text_14">question_description</div>
        <div class="question_qcm d-none alert alert-primary mw70 text-center mx-auto">
            Vote à préférences multiples
            <span class="option-strict d-none">strict</span>
            <span class="option-max d-none">max</span> :
            <i><span class="option-until d-none">Jusqu'à</span> <span class="nb-choice">0</span> options choisies par le votant</i>
        </div>
        <div class="option_list container px-0 "></div>
        <div class="question_qcm_note d-none mw80 text-center mx-auto text-muted">
            <small>
            Pourcentages exprimés sur le nombre total de choix possibles pour l’ensemble des votants :<br />
            <span class="nb_participation_total"></span> votants
            jusqu’à <span class="nb-choice">0</span> choix par votant
            = <span class="choice_nb"></span> choix possibles
            </small>
        </div>
        <div class="text-muted text-center mw80 mx-auto">
            <small>Les résultats exprimés en pourcentage sont arrondis au centième.</small>
        </div>
    </div>

    <div class="option_item row">
        <div class="col-8 px-3 cpcolor-blue1 cp_text_14_bold  px-4 option_title div_title_span"></div>
        <div class="col-2 cpcolor-white div_value_span2 bg-secondary px-4 cp_text_14_bold_white text-center div_title_span"></div>
        <div class="col-2 cpcolor-white div_value_span bgcolor-blue px-4 cp_text_14_bold_white div_title_span"></div>
        <div class="col-12 progline bg-transparent radius-0"></div>
    </div>
</div>

