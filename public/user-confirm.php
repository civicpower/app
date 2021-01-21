<?php
project_css_js($fw);
$fw->include_css('user-confirm');
$fw->include_js('user-confirm');
$fw->set_canonical('/user-confirm');
$fw->smarty->display('user-confirm.tpl');
$fw->go();
?>