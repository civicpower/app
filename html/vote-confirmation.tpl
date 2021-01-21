{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}


<div id="init_hidden" class="d-none">
{include file="block-menu-user.tpl" }
    <div class="small-box text-center">
        <div class="mt-4">
            <img src="/images/svg/icone-avote-red.svg" width="20%"/>
        </div>
        <div class="txt-a-vote cpcolor-red mb-4 font-weight-bold">
            A voté !
        </div>
        <div class="mx-auto mw50 cpcolor-blue1 mb-0 font-weight-bold">
            Vous serez notifié du résultat de la consultation le <span class="ballot_end_date">__/__/__</span> à <span class="ballot_end_hour">__H__</span>.
        </div>
        <div id="receive-email" style="display: none;" class="mx-auto mw50 cpcolor-blue1 mb-4 font-weight-bold">
            Pour recevoir les résultats par email,<br>
            <a href="/user-email">cliquez ici !</a>
        </div>
        <div class="mt-4" id="div_see_results_live">
            <a class="ballot_token_param btn-retour-vote btn btn-light btn-civicpower">Voir la tendance des votes</a>
        </div>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }

