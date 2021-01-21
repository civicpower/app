<?php
project_css_js($fw);
$fw->include_css('sms');
$fw->include_js('sms');
$fw->set_canonical('/sms');
$fw->smarty->display('sms.tpl');
$fw->go();
?>