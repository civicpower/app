<footer class="bottom-footer small-box text-center valign-center">
    <div class="footer-inner">

        <div class="footer-side">
            <a href="https://tc.civicpower.io/mentions-legales-app/">Mentions légales</a>
            - <a href="https://tc.civicpower.io/donnees-personnelles-app/">Données personnelles</a>
            - <a href="https://tc.civicpower.io/charte-app/">Charte</a>
            - <a href="https://tc.civicpower.io/contact-app/">Contact</a>
        </div>
        <div class="footer-side footer-copyright">&copy; {date("Y")} Fondation Civicpower, tous droits réservés</div>
    </div>
</footer>
</div>
</div> <!-- /container -->
</main>

<div class="modal fade" id="modal-alert">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Civicpower</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="p-text"></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    tarteaucitron.services.mycustomservice = {
        "key": "mycustomservice",
        "type": "ads|analytic|api|comment|other|social|support|video",
        "name": "MyCustomService",
        "needConsent": true,
        "cookies": ['cookie', 'cookie2'],
        "readmoreLink": "/custom_read_more", // If you want to change readmore link
        "js": function () {
            "use strict";
        },
        "fallback": function () {
            "use strict";
        }
    };
    (tarteaucitron.job = tarteaucitron.job || []).push('mycustomservice');
</script>
