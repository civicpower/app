{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}




<div id="init_hidden" class="d-none">
{include file="block-menu-user.tpl" }

    <div class="small-box radius-bottom">
        <div class="text-center civicpower-title1 cpcolor-blue2 mt-4">Bienvenue <span id="span_user_firstname"></span>,</div>
        <div class="text-center mb-4 civicpower-title2 cpcolor-blue2">
            votre compte est activé.
        </div>
        <div class="text-center cpcolor-blue2 font-weight-bold mb-3 div-title-ballot">
            Ces consultations pourraient vous intéresser
        </div>
        <div class="mb-4 font-weight-bold">
            <div id="ballot_list" class="valign-center mh-vh">

            </div>
        </div>
    </div>
</div>
{include file="block-menu-user-bottom.tpl" }


<div id="ballot-stock" class="d-none">
    <button class="ballot-item btn btn-lg btn-block text-left">
        {*<div class="vote-info">
            Vote du <span class="date-start"></span>
            au <span class="date-end"></span>
        </div>*}
        <div class="mt-1">
            <div class="row">
                <div class="col-3">
                    <img src="https://{$smarty.env.BO_URL}/uploads/pp/default.png" class="img-asker rounded-circle img-fluid"/>
                </div>
                <div class="col-9 valign-center">
                    <span class="ballot-asker_name">Asker Name</span>
                    <span class="ballot-title font-weight-bold">Ballot Title</span>
                    {*<div class="ballot-description">Ballot Description</div>*}
                </div>
            </div>
        </div>
    </button>
</div>
