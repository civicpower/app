<?php
try {
    if (isset($this)) {
        #$this->set_header_file('entete.tpl');
        $this->set_header_file($_ENV['HTML_ROOT'].'entete.tpl');
        #$this->set_footer_file('piedpage.tpl');
        $this->set_footer_file($_ENV['HTML_ROOT'].'piedpage.tpl');
        $this->set_body_class('body_onlyphone nobg');
        $this->add_entete('<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />');
        $this->add_entete('<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />');
        $this->add_entete('<meta property="og:site_name" content="' . htmlentities($_ENV['LOGO_NAME'] . ' - ' . $_ENV['SITE_SOUS_TITRE']) . '" />');
        $this->add_entete('<meta property="og:locale" content="fr_FR" />');
        $this->add_entete('<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">');
        $this->add_entete('<script type="text/javascript">var BO_URL = "' . $_ENV['BO_URL'] . '";</script>');
        if (!isset($this->remove_search_header) || $this->remove_search_header == false) {
            $this->add_entete('
    <!--[if gte IE 9]>
      <style type="text/css">
        .gradient {
           filter: none;
        }
      </style>
    <![endif]-->
    
    ');
    }
    $this->add_entete('
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@civicpowerio">
        <meta name="twitter:creator" content="@civicpowerio">
        <script type="text/javascript" src="/vendor/tarteaucitron/tarteaucitron.js"></script>
        <script type="text/javascript">
        tarteaucitron.init({
          "privacyUrl": "https://www.civicpower.io/donnees-personnelles-app", /* Privacy policy url */

          "hashtag": "#tarteaucitron", /* Open the panel with this hashtag */
          "cookieName": "tarteaucitron", /* Cookie name */
    
          "orientation": "top", /* Banner position (top - bottom) */
                           
          "showAlertSmall": false, /* Show the small banner on bottom right */
          "cookieslist": false, /* Show the cookie list */
                           
          "showIcon": false, /* Show cookie icon to manage cookies */
          "iconPosition": "BottomRight", /* BottomRight, BottomLeft, TopRight and TopLeft */

          "adblocker": false, /* Show a Warning if an adblocker is detected */
                           
          "DenyAllCta" : true, /* Show the deny all button */
          "AcceptAllCta" : true, /* Show the accept all button when highPrivacy on */
          "highPrivacy": true, /* HIGHLY RECOMMANDED Disable auto consent */
                           
          "handleBrowserDNTRequest": false, /* If Do Not Track == 1, disallow all */

          "removeCredit": false, /* Remove credit link */
          "moreInfoLink": true, /* Show more info link */

          "useExternalCss": false, /* If false, the tarteaucitron.css file will be loaded */
          "useExternalJs": false, /* If false, the tarteaucitron.js file will be loaded */

          "cookieDomain": ".civicpower.io", /* Shared cookie for multisite */
                          
          "readmoreLink": "", /* Change the default readmore link */

          "mandatory": true, /* Show a message about mandatory cookies */
        });
        </script>
    ');
    }
} catch (Exception $ex) {
}