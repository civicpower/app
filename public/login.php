<?php
project_css_js($fw);
$fw->include_css('label-placeholder');
$fw->include_css('login');
$fw->include_js('login');
$fw->add_js('sha1.min.js');
$fw->set_canonical('/login');
$fw->smarty->display('login.tpl');
$fw->go();
?>