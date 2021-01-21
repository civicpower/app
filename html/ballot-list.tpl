{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}

<div id="init_hidden" class="d-none">
    {include file="block-menu-user.tpl" }

    <div class="small-box">
        <input type="hidden" name="list_mode" id="list_mode" value="{$list_mode}"/>
        <div class="cp_text_14_bold text-center">
            {if $list_mode eq "ballot"}
                Consultations
            {elseif $list_mode eq "vote"}
                Mes votes
            {elseif $list_mode eq "result"}
                Résultats des consultations terminées
            {/if}
        </div>
        <div class="no-vote d-none text-center civicpower-title1 alert alert-danger">
            <span class="user-firstname"></span>

            {if $list_mode eq "ballot"}
                vous n’avez pas de consultation en cours
            {elseif $list_mode eq "vote"}
                vous n'avez participé à aucune consultation
            {elseif $list_mode eq "result"}
                Aucune consultation terminée n'est disponible
            {/if}
        </div>
        <div id="ballot_list" class="">

        </div>
    </div>

    <div id="ballot-stock" class="d-none">
        <button class="ballot-item btn btn-block text-left">
            {*
            <div class="vote-info">
                Vote du <span class="date-start"></span>
                au <span class="date-end"></span>
            </div>
            *}
            <div class="mt-1">
                <div class="row">
                    <div class="col-3 text-center">
                        <img src="https://{$smarty.env.BO_URL}/uploads/pp/default.png" class="img-asker rounded-circle img-fluid"/>
                    </div>
                    <div class="col-9">
                        <div class=""><span class="ballot-state cp_text_10_bold_maj"></span></div>
                        <span class="ballot-asker_name cp_text_14_bold_maj">Asker Name</span>
                        <span class="ballot-title cp_text_14_bold_maj">Ballot Title</span>
                        <div class="ballot-description cp_text_13">Ballot Description</div>
                        <div class="vote-info cp_text_13 mt-2">
                            <div class="ballot-dates">Du <span class="date-start"></span> au <span class="date-end"></span></div>
                            <div class="ballot-participation"><span class="nb-participants"></span> participants</div>
                        </div>
                        <div class="div-share mt-2">
                            <div class="cp_text_10_bold">Partager ou suivre la consultation</div>
                            <div>
                                <a class="url-update" target="_blank" href="https://twitter.com/share?url=__url__&text=__text__"><img src="/images/svg/social/ico-twt.svg" class="social-icon"></a>
                                <a class="url-update" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=__url__&p[title]=__text__"><img src="/images/svg/social/ico-fb.svg" class="social-icon"></a>
                                <a class="url-update" target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=__url__&title=__text__"><img src="/images/svg/social/ico-link.svg" class="social-icon"></a>
                                <a class="url-update d-none" target="_blank" href="https://scan.civicpower.io"><img src="/images/svg/social/ico-bc.svg" class="social-icon"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </button>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }

