<?php
project_css_js($fw);
$fw->include_css('unsubscribe');
$fw->include_js('unsubscribe');
$fw->set_canonical('/unsubscribe');
$fw->smarty->display('unsubscribe.tpl');
$fw->go();
?>