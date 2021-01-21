<?php
project_css_js($fw);
$fw->include_css('vote-confirmation');
$fw->include_js('vote-confirmation');
$fw->set_canonical('/vote-confirmation');
$fw->smarty->display('vote-confirmation.tpl');
$fw->go();
?>