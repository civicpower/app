<?php
define("NB_SUBSCRIPTION_STEPS",5);
define("PASSWORD_MIN_LENGTH",8);
function civicpower_inject_meta(&$smarty){
    $shortcode = gget("shortcode");
    $ballot_token = gget("ballot_token");
    $url = "https://".$_ENV['API_URL']."/get_meta?shortcode=".urlencode($shortcode)."&ballot_token=".urlencode($ballot_token);
    $data = file_get_contents($url);
    if(is_string($data) && strlen($data)>0){
        $data = json_decode($data,true);
        if(is_array($data) && count($data)>0){
            if(isset($data["data"]["ballot_title"]) && is_string($data["data"]["ballot_title"]) && strlen($data["data"]["ballot_title"])>0){
                $smarty->assign('seo_title',$data["data"]["ballot_title"]);
            }
            if(isset($data["data"]["ballot_description"]) && is_string($data["data"]["ballot_description"]) && strlen($data["data"]["ballot_description"])>0){
                $smarty->assign('seo_description',$data["data"]["ballot_description"]);
            }
            return $data["data"];
        }
    }
}
?>