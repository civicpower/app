<?php
function navigateur(){
	$browser = "Autre";
	$user_agent=getenv("HTTP_USER_AGENT");
	if ((preg_match("~Nav~", $user_agent)) || (preg_match("~Gold~", $user_agent)) ||
	(preg_match("~X11~", $user_agent)) || (preg_match("~Mozilla~", $user_agent)) ||
	(preg_match("~Netscape~", $user_agent))
	AND (!preg_match("~MSIE~", $user_agent))
	AND (!preg_match("~Konqueror~", $user_agent))
	AND (!preg_match("~Firefox~", $user_agent))
	AND (!preg_match("~Safari~", $user_agent)))
	        $browser = "Netscape";
	elseif (preg_match("~Opera~", $user_agent))
	        $browser = "Opera";
	elseif (preg_match("~MSIE~", $user_agent)){
			$env=$user_agent;
			if(preg_match("~MSIE 8~",$env)){
		        $browser = "MSIE8";
		    }elseif(preg_match("~MSIE 7~",$env)){
		        $browser = "MSIE7";
		    }elseif(preg_match("~MSIE 6~",$env)){
		        $browser = "MSIE6";
		    }elseif(preg_match("~MSIE 5~",$env)){
		        $browser = "MSIE5";
		    }
	}elseif (preg_match("~Lynx~", $user_agent))
	        $browser = "Lynx";
	elseif (preg_match("~WebTV~", $user_agent))
	        $browser = "WebTV";
	elseif (preg_match("~Konqueror~", $user_agent))
	        $browser = "Konqueror";
	elseif (preg_match("~Safari~", $user_agent)){
	        if(preg_match("~iPhone OS~", $user_agent)){
				$browser = "iPhone";
			}elseif(preg_match("~Chrome~",$user_agent)){
	 	       $browser = "Chrome";
	 	    }else{
	 	       $browser = "Safari";
	 	    }
	}elseif (preg_match("~Firefox~", $user_agent)){
			$browser = "Firefox";
	}elseif ((preg_match("~bot~i", $user_agent)) || (preg_match("~Google~", $user_agent)) ||
	(preg_match("~Slurp~", $user_agent)) || (preg_match("~Scooter~", $user_agent)) ||
	(preg_match("~Spider~", $user_agent)) || (preg_match("~Infoseek~", $user_agent)))
	        $browser = "Bot";
	else
	        $browser = "Autre";

	return $browser;
}
?>
