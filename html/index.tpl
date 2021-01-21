{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">
    <div class="small-box small-box-transparent text-center">
        <div class="text-center mt-4 mb-4">
            <img class="logo-top-round" src="/images/svg/logo-bleu-civicpower.svg" alt="Logo Civicpower"/>
        </div>
        <div class="if-not-connected mt-4 valign-center mh-vh">
            <div id="custom-yes">
                <div class="ballot-item text-left">
                    <div class="mt-1">
                        <div class="row">
                            <div class="col-3">
                                <img src="https://{$smarty.env.BO_URL}/uploads/pp/default.png" class="img-asker rounded-circle img-fluid"/>
                            </div>
                            <div class="col-9">
                                {*                            <div class="ballot-asker_name font-weight-bold">Asker Name</div>*}
                                <div class="ballot-title cp_text_14_bold_maj"></div>
                                <div class="ballot-description cp_text_14"></div>
                                <div class="vote-info cp_text_13 mt-2">
                                    <div class="ballot-dates">Du <span class="date-start"></span> au <span class="date-end"></span></div>
                                    <div class="ballot-participation"><span class="nb-participants"></span> participants</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mx-auto mt-1 mb-2 cp_text_14">
                    Pour voter, créez votre compte ou<br/>connectez-vous
                </div>
            </div>
            <div id="custom-no">
                <div class="cp_text_16_bold mt-4 mx-auto">
                    Votre application de vote
                </div>
            </div>
            <div class="mb-2 mt-4">
                <a id="link-subscribe" href="/subscribe" class="btn cp_button_red">Créer un compte</a>
            </div>
            <div class="mb-4 mt-2">
                <a class="btn cp_button_blue" href="/login">Connexion</a>
            </div>
            <div>
                <a class="cp_text_12" href="/lost-password">Mot de passe oublié</a>
            </div>
        </div>
    </div>

    {include file="block-social.tpl"}
    <div id="ballot-stock" class="d-none">
        <button class="ballot-item btn btn-lg btn-block text-left">
            <div class="vote-info">
                Vote du <span class="date-start"></span>
                au <span class="date-end"></span>
            </div>
            <div class="mt-1">
                <div class="row">
                    <div class="col-3">
                        <img src="https://{$smarty.env.BO_URL}/uploads/pp/default.png" class="img-asker rounded-circle img-fluid"/>
                    </div>
                    <div class="col-9 valign-center">
                        <span class="ballot-asker_name font-weight-bold">Asker Name</span>
                        <span class="ballot-title">Ballot Title</span>
                        <div class="ballot-description">Ballot Description</div>
                    </div>
                </div>
            </div>
        </button>
    </div>
</div>
