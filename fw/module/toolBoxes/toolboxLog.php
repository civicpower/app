<?php
/**
 * toolboxLog
 * @access              public
 * @author              C2
 * purpose              a collection of publication tools

    [function]          [description]
    view_logs           HTTP logs for devs
    crons_error_logs    crons activity in error_log
    get_ip
    get_timestamp       time()
    get_url
    get_url_https       $_SESSION{'iduser'}
    get_usersession     session_id()
    get_userreferer     
    get_useragent
    put_app_log
    log_verifyagent

*/
class toolboxLog {

    // Get the power
    	public $civicpower;
        public function __construct() {
            $this->civicpower = $GLOBALS['civicpower'];
        }

    // allow toolboxDatabase::getInstance()->->game_over( instead of $ttt->toolboxDatabase
    // https://stackoverflow.com/questions/1449362/origin-explanation-of-classgetinstance
        public static $_instance;
        public static function getInstance() {
            if ( !(self::$_instance instanceof self) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

	//*  C2        */
    //*  get IP    */
        public function get_ip() {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            } 
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else { return $_SERVER['REMOTE_ADDR']; }
        }

    //*  C2               */
    //*  get timestamp    */
        public function get_timestamp() {
            return time();
        }

    //*  C2               */
    //*  get url          */
        public function get_url() {
            return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        public function get_url_https() {
            return "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

    //*  C2           */
    //*  get _user    */
        public function get_userid() {
            if (!empty($_SESSION{'iduser'})) { return $_SESSION{'iduser'};}
            else { return FALSE; }
        }

    //*  C2           */
    //*  get _user    */
        public function get_usersession() {
            if (!empty(session_id())) {return session_id();}
            else { return FALSE; }
        }

    //*  C2                 */
    //*  get_userreferer    */
        public function get_userreferer() {
            if(!empty($_SERVER['HTTP_REFERER'])) { return $_SERVER['HTTP_REFERER'];}
            else { return FALSE; }
        }

    //*  C2               */
    //*  get_useragent    */
        public function get_useragent() {
            if(!empty($_SERVER['HTTP_USER_AGENT'])) { return $_SERVER['HTTP_USER_AGENT'];}
            else { return FALSE; }
        }

    /* Debug APP pages
    * @access          public
    * @param           content
    * @return          TRUE/FALSE
    * @author          C2
    * @purpose         Debug APP pages
    * @todo
    */
    public function put_app_log ($params = "") {
        return $this->civicpower->toolboxPublish->call_civicpower_api_bc(
            array(
                 'verb'          => 'put_app_log'
                ,'params'        => array(
                     "app_log_url"     => ( ( isset($params['app_log_url'])&&($params['app_log_url']<>"") ) ? $params['app_log_url'] : false)
                    ,"app_log_html"     => ( ( isset($params['app_log_html'])&&($params['app_log_html']<>"") ) ? $params['app_log_html'] : false)
               )
            )
        );
    }


	/* log_verifyagent
    * @access          public
    * @param           the user agent
    * @return 		   TRUE = it's a bot
    * @author          C2
    * @purpose         do not log when it's a bot
    * @toupdate list
        SELECT count(`lse_agent`), `lse_agent` FROM `log_2018_11_23_sniff_addEdit` GROUP by `lse_agent` order by count(`lse_agent`) DESC 
    * @todo
    *   stats in a DB
    */
    public function log_verifyagent ($agent = "") {
        // Init
            // Agent
                if ( !isset($agent) || ($agent=="") ) { $agent = $this->get_useragent(); }
            // Targets
				$bot = array(
				 	 'Googlebot' // Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)
					,'Pinterestbot' // Mozilla/5.0 (compatible; Pinterestbot/1.0; +http://www.pinterest.com/bot.html)
					,'TurnitinBot' // TurnitinBot (https://turnitin.com/robot/crawlerinfo.html)
					,'Nimbostratus-Bot' // Mozilla/5.0 (compatible; Nimbostratus-Bot/v1.3.2; http://cloudsystemnetworks.com)
					,'SurdotlyBot' // Mozilla/5.0 (compatible; SurdotlyBot/1.0; +http://sur.ly/bot.html)
					,'MegaIndex.ru' // Mozilla/5.0 (compatible; MegaIndex.ru/2.0; +http://megaindex.com/crawler)
					,'MJ12bot' // Mozilla/5.0 (compatible; MJ12bot/v1.4.8; http://mj12bot.com/)
					,'SemrushBot' // Mozilla/5.0 (compatible; SemrushBot/2~bl; +http://www.semrush.com/bot.html)
					,'bingbot' // Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)
					,'Exabot' // Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)
					,'adscanner' // Mozilla/5.0 (compatible; adscanner/)
					,'Qwantify' // Mozilla/5.0 (compatible; Qwantify/2.4w; +https://www.qwant.com/)/2.4w
					,'Uptimebot' // Uptimebot.org - Free website monitoring
					,'Scrapy/' // Scrapy/1.5.1 (+https://scrapy.org)
					,'SEMrushBot' // SEMrushBot
					,'Mail.RU_Bot' // Mozilla/5.0 (compatible; Linux x86_64; Mail.RU_Bot/2.0; +http://go.mail.ru/help/robots)
					,'AhrefsBot' // Mozilla/5.0 (compatible; AhrefsBot/5.2; +http://ahrefs.com/robot/)
					,'UptimeRobot' // Mozilla/5.0+(compatible; UptimeRobot/2.0; http://www.uptimerobot.com/)
					,'Baiduspider' // Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)
					,'opensiteexplorer.org/dotbot' // Mozilla/5.0 (compatible; DotBot/1.1; http://www.opensiteexplorer.org/dotbot, help@moz.com)
					,'ZoominfoBot' // ZoominfoBot (zoominfobot at zoominfo dot com)
					,'Pingdom.com' // Pingdom.com_bot_version_1.4_(http://www.pingdom.com/)
					,'linguee.com/bot' // Linguee Bot (http://www.linguee.com/bot; bot@linguee.com)
					,'trendictionbot' // Mozilla/5.0 (Windows; U; Windows NT 6.0; en-GB; rv:1.0; trendictionbot0.5.0; trendiction search; htt
					,'glutenfreepleasure' //  Mozilla/5.0 (compatible; Gluten Free Crawler/1.0; +http://glutenfreepleasure.com/)
					,'elefent' //  elefent/1.4 (web crawler :: robots.txt exclude elefent; https://crawler.elefent.net; webmaster at elefent dot net)
					,'archive.org_bot' // Mozilla/5.0 (compatible; archive.org_bot +http://archive.org/details/archive.org_bot)
					,'oBot' // Mozilla/5.0 (compatible; oBot/2.3.1; http://filterdb.iss.net/crawler/)
					,'DuckDuckGo' //  Mozilla/5.0 (compatible; DuckDuckGo-Favicons-Bot/1.0; +http://duckduckgo.com)
					,'MojeekBot' // Mozilla/5.0 (compatible; MojeekBot/0.6; +https://www.mojeek.com/bot.html)
					,'SEOkicks' // Mozilla/5.0 (compatible; SEOkicks; +https://www.seokicks.de/robot.html)
					,'Slackbot' // Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)
					,'SeznamBot' // Mozilla/5.0 (compatible; SeznamBot/3.2; +http://napoveda.seznam.cz/en/seznambot-intro/)
					,'linkdexbot' // Mozilla/5.0 (compatible; linkdexbot/2.0; +http://www.linkdex.com/bots/)
					,'Twitterbot' // Twitterbot/1.0
					,'Applebot' // Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2 Safari/600.2.5 (Applebot/0.1; +http://www.apple.com/go/applebot)
					,'YandexBot' // Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)
					                    // Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B411 Safari/600.1.4 (compatible; YandexMobileBot/3.0; +http://yandex.com/bots)
					,'zgrab/' // Mozilla/5.0 zgrab/0.x
				);
        // Just a last verification for a soft warning
            if (in_array($agent, $bot)) { return TRUE; }
            else { return FALSE; }
    } // log_verifyagent

}