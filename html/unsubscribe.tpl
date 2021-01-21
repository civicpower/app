{seo_title}Application Civicpower{/seo_title}
{seo_description}{$seo_title}{/seo_description}
<div id="init_hidden" class="d-none">
    <div class="small-box small-box-transparent text-center">
        <div class="text-center mt-4 mb-4">
            <img class="logo-top-round" src="/images/svg/logo-bleu-civicpower.svg" alt="Logo Civicpower"/>
        </div>
        <form id="form_unsubscribe" class="mt-4 valign-center mh-vh">
            <div class="cp_text_16_bold mt-4 mx-auto">Se désinscrire des notifications</div>
            <div class="unsub-to">
                <span id="to-label"></span> &nbsp;<span class="font-weight-bold" id="to-value"></span>
            </div>
            <div id="unsubscribe-items">
                <div>
                    <label class="unsubscribe-item d-none" id="item-asker">
                        <input id="input_asker" type="checkbox"/>
                        <span>Je ne veux plus recevoir de notifications de nouvelles consultations de la part de <span class="font-weight-bold" id="asker-name"></span></span>
                    </label>
                </div>
                <div>
                    <label class="unsubscribe-item">
                        <input type="checkbox" name="ballot"/>
                        <span>Je ne veux plus recevoir de notifications de nouvelles consultations</span>
                    </label>
                </div>
                <div>
                    <label class="unsubscribe-item">
                        <input type="checkbox" name="all"/>
                        <span>Je ne veux plus recevoir de communications de l'application Civicpower</span>
                    </label>
                </div>
            </div>
            <div id="div-submit" class=" mt-4">
                <button id="btn-submit" class="btn btn-primary" type="button">Enregistrer</button>
                <a class="btn btn-light" href="/ballot-list">Annuler</a>
            </div>
        </form>
    </div>
</div>
