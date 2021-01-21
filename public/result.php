<?php
project_css_js($fw);
$fw->include_css('result');
$fw->include_js('result');
$fw->include_js('countdown');
$fw->add_css('div-countdown.css');
$fw->smarty->assign('shortcode',gget("shortcode"));
$fw->smarty->assign('ballot_token',gget("ballot_token"));
$data = civicpower_inject_meta($fw->smarty);
$canonical = 'result?ballot_token='.grequest("ballot_token");
if(isset($data['ballot_shortcode'])){
    $canonical = 'r/'.$data['ballot_shortcode'];
}else if(request_exists("ballot_token")){
    $canonical = 'r/'.grequest("ballot_token");
}
$fw->set_canonical($canonical);
if(isset($data['asker_token']) && is_string($data['asker_token']) && strlen($data['asker_token'])>0) {
    $fw->set_og_image("https://" . $_ENV['BO_URL'] . "/uploads/pp/" . $data['asker_token'] . '.png');
}
$fw->smarty->display('result.tpl');
$fw->go();
?>