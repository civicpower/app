<?php
project_css_js($fw);
$fw->include_css('404');
$fw->include_js('404');
$fw->set_canonical('/404');
$fw->smarty->display('404.tpl');
$fw->go();
?>