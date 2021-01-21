<?php
class htmlpage{
	var $smarty;
	var $tplfile;
	var $doctype;
	var $title;
	var $prefix_title;
	var $description;
	var $canonical;
	var $publish_date;
	var $og_image;
	var $fb_appid;
	var $body_class;
	var $author;
	var $keywords_tab;
	var $script_tab;
	var $css_tab;
	var $entete_tab;
	var $onload;
	var $body;
	var $replacements;
	var $header;
	var $footer;
	var $write_html;
	var $curdir;
    public function __construct($cache=true,$jquery=true,$tpldir=null){
		// Smarty's HTML root
	    if(!isset($_ENV['HTML_ROOT'])){
	        $_ENV['HTML_ROOT'] = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../html/");
	    }
		// Load Smarty
		module(str_replace("//", "/", "/module/smarty3/libs/SmartyBC.class.php"));
		$this->smarty = new SmartyBC;
		$this->smarty->force_compile = true;
		$this->smarty->compile_check = false;
		$this->smarty->debugging = false;
		if(!is_null($tpldir)){
		    $this->smarty->setTemplateDir($tpldir);
		    $this->curdir = preg_replace("~\/$~","",dirname($tpldir));
        }else{
        	//$this->curdir = curdir();
		    $this->curdir = str_replace("//", "/", $_ENV['HTML_ROOT']);
		    if(isset($GLOBALS['template_dir'])){
                $this->smarty->setTemplateDir($this->curdir . $GLOBALS['template_dir']);
            }else{
                //$this->smarty->setTemplateDir($this->curdir . '/html');
                $this->smarty->setTemplateDir($this->curdir);
            }
        }
		$template_dir = $this->smarty->template_dir;
        $this->smarty->trusted_dir[] = str_replace("/html","/sys",$template_dir);
		if(is_array($template_dir) && count($template_dir)>0){
			$template_dir = array_shift($template_dir);
		}
		$this->smarty->compile_dir = $template_dir . '/cache';
		$this->smarty->setConfigDir($template_dir);
		$this->smarty->setPluginsDir($template_dir.'smarty_plugins');
		$this->doctype='<!DOCTYPE html>'."\n";
		$this->script_tab = array();
		$this->keywords_tab = array();
		$this->css_tab = array();
		$this->entete_tab=array();
		$this->onload=array();
		$this->body=array();
		$this->replacements=array();
		$this->prefix_title='';
		$this->title='';
		$this->description='';
		$this->canonical=null;
		$this->publish_date=null;
		$this->og_image='/images/og/default.jpg';
		$this->fb_appid=null;
		$this->body_class=null;
		$this->author='';
		$this->header='';
		$this->footer='';
		$this->write_html=true;
		if($jquery){
			$this->add_script(str_replace("//", "/", $_SERVER['DOCUMENT_ROOT']."../fw/module/jquery/jquery.js"));
		}
		$config_dir = $this->smarty->getConfigDir();
		$config_dir = array_shift($config_dir);
		require($config_dir . '/config.php');
		ob_start();
	}
	function set_write_html($val){
		$this->write_html = $val;
	}
	function set_header_file($file){
		$this->header=$this->html($file,true);
	}
	function clear_header_file(){
		$this->header='';
	}
	function set_footer_file($file){
		$this->footer=$this->html($file,true);
	}
	function clear_footer_file(){
		$this->footer='';
	}
	function apply_tab($tab){
		foreach($tab as $k=>$v){
			$this->apply_param($k,$v);
		}
	}
	function assign($name,$value){
		$this->add_replacements($name,$value);
	}
	function apply_param($name,$value){
		$this->add_replacements($name,$value);
	}
	function add_replacements($name,$value){
		$this->replacements[]=array('name'=>$name,'value'=>$value);
	}
	function getval($my_name){
		$res=null;
		foreach($this->replacements as $v){
			if(strtolower($v["name"]) == strtolower($my_name)){
				$res=$v['value'];
				break;
			}
		}
		return $res;
	}
	function add_script($file,$include=false){
	    $fin="";
	    if(preg_match("~(\?.*?)$~",$file,$match)){
            $fin=$match[1];
            $file = preg_replace("~\?.*?$~","",$file);
        }
		if(!in_array($file, $this->script_tab)){
			$possibles = array(
				$file,
				$file.'.js',
				//$this->curdir.'/js/'.$file,
				'js/'.$file,
				//$this->curdir.'/js/'.$file.'.js'
				'js/'.$file.'.js'
			);
			$done=false;
			foreach($possibles as $f){
				if(file_exists($f) && is_file($f)){
					$this->script_tab[]=($include?'<include>':'').webdir($f).$fin;
					$this->script_tab = array_unique($this->script_tab);
					$done=true;
					break;
				}
			}
			if(!$done){
				$this->script_tab[]=$file;
				$this->script_tab = array_unique($this->script_tab);
			}
		}
	}
	function include_script($file){
		$this->add_script($file,true);
	}
	function add_js($str){
		$this->add_script($str);
	}
	function include_js($str){
		$this->include_script($str,true);
	}
	function add_style($file,$include=false){
	    $fin="";
	    if(preg_match("~(\?.*?)$~",$file,$match)){
            $fin=$match[1];
            $file = preg_replace("~\?.*?$~","",$file);
        }
		if(!in_array($file, $this->css_tab)){
			$possibles = array(
				$file,
				$file.'.css',
				//$this->curdir.'/css/'.$file,
				'css/'.$file,
				//$this->curdir.'/css/'.$file.'.css'
				'css/'.$file.'.css'
			);
			foreach($possibles as $f){
				if(file_exists($f) && is_file($f)){
					$this->css_tab[]=($include?'<include>':'').webdir($f).$fin;
					$this->css_tab = array_unique($this->css_tab);
					break;
				}
			}
		}
	}
	function add_css($file){
		$this->add_style($file);
	}
	function include_css($file){
		$this->add_style($file,true);
	}
	function include_style($file){
		$this->add_style($file,true);
	}
	function add_entete($str){
		$this->entete_tab[]=$str;
	}
	function add_onload($str){
		$this->onload[]=$str;
	}
	function add_body($str){
		$this->body[]=$str;
	}
	function set_prefix_title($str){
		$this->prefix_title = $str;
	}
	function set_title($str){
		$this->title = $str;
	}
	function add_keyword($str){
		if(is_array($str)){
			foreach($str as $k=>$v){
				$this->keywords_tab[]= $v;
			}
		}else{
			$this->keywords_tab[]= $str;
		}
	}
	function set_description($str){
		$this->description = $str;
	}
	function set_canonical($str){
		$this->canonical = $str;
	}
	function set_publish_date($str){
		$this->publish_date = $str;
	}
	function set_og_image($str){
		$this->og_image = $str;
	}
	function set_fb_appid($str){
		$this->fb_appid = $str;
	}
	function set_body_class($str){
		$this->body_class = $str;
	}
	function set_author($str){
		$this->author = $str;
	}
	function run_template(){
		if(isset($this->smarty->seo_title) && strlen($this->smarty->seo_title)>0){
			$this->title = $this->smarty->seo_title;
		}
		if(isset($this->smarty->seo_description) && strlen($this->smarty->seo_description)>0){
			$this->description = $this->smarty->seo_description;
		}
		$this->smarty->assign('HTML_DOCTYPE',$this->doctype);
		$this->smarty->assign('HTML_PREFIX_TITLE',$this->prefix_title);
		$this->smarty->assign('HTML_TITLE',$this->title);
		$this->smarty->assign('HTML_ENTETE',implode("\n",$this->entete_tab));
		$this->smarty->assign('HTML_ONLOAD',implode("\n",$this->onload));
		$this->smarty->assign('HTML_HEADER',$this->header);
		$this->smarty->assign('HTML_BODY',$this->body);
		$this->smarty->assign('HTML_FOOTER',$this->footer);
		$this->smarty->assign('HTML_TAB_CSS',array_merge($this->css_tab));
		$this->smarty->assign('HTML_TAB_JS',array_merge($this->script_tab));
		$this->smarty->assign('HTML_META_CANONICAL',$this->canonical);
		$this->smarty->assign('HTML_META_PUBLISH_DATE',$this->publish_date);
		$this->smarty->assign('HTML_META_OG_IMAGE',$this->og_image);
		$this->smarty->assign('HTML_META_FB_APPID',$this->fb_appid);
		$this->smarty->assign('HTML_BODY_CLASS',$this->body_class);
		$this->smarty->assign('HTML_META_DESCRIPTION',$this->description);
		$this->smarty->assign('HTML_META_KEYWORDS',implode(',',$this->keywords_tab));
		$this->smarty->assign('HTML_META_AUTHOR',$this->author);
		$this->smarty->assign('WRITE_HTML',$this->write_html);
		module(str_replace("//", "/", "/module/browser.php"));
		$this->smarty->assign('BROWSER',navigateur());
	}
	function html($f,$return=false){
		$possibles = array(
			$f,
			$f.'.tpl',
			$f.'.html',
			$f.'.htm',
			$this->curdir.'/'.$f,
			$this->curdir.'/'.$f.'.tpl',
			$this->curdir.'/'.$f.'.html',
			$this->curdir.'/'.$f.'.htm'
		);
		$ok=false;
		$file=null;
		foreach($possibles as $ff){
			if(file_exists($ff) && is_file($ff)){
				$ok=true;
				$file=$ff;
				continue;
			}
		}
		if(file_exists($file)){
			if($return){
				return file_get_contents($file);
			}else{
				echo file_get_contents($file);
			}
		}else{
			//echo('LE FICHIER '.$f.' N\'EXISTE PAS parmi ');
			//vardump($possibles);
		}
	}
	function go($tplfile = 'html'){
		$this->body = ob_get_contents();
		$this->tplfile = $tplfile;
		ob_end_clean();
		$this->run_template();
		$this->smarty->display(dirname(__FILE__) . '/'.$tplfile.'.tpl');
	}
	function fetch($tplfile = 'html'){
		$this->body = ob_get_contents();
		$this->tplfile = $tplfile;
		ob_end_clean();
		$this->run_template();
		return $this->smarty->fetch(dirname(__FILE__) . '/'.$tplfile.'.tpl');
	}
}
?>