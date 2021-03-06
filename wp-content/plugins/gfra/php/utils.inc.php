<?php
function formatCp($cp) {
		$cp = " ".$cp;
/*	$cp = trim($cp);
	if(strlen($cp) == 5) {
		$cp = substr($cp,0,2).' '.substr($cp,2,3);
	} else {
		$cp = '\u00a0'.$cp;
	}*/
	return $cp;
}
function formatPhoneNumber($phoneNumber) {
    $phoneNumber = trim($phoneNumber);
    if(strstr($phoneNumber, " ")===false && strlen($phoneNumber) == 10) {
    	$phoneNumber = substr($phoneNumber,0,2)." ".substr($phoneNumber,2,2)." ".substr($phoneNumber,4,2)." ".substr($phoneNumber,6,2)." ".substr($phoneNumber,8,2);
    }
    return $phoneNumber;
}

function arrayToCsv( array &$fields, $delimiter = ';', $enclosure = '"', $encloseAll = true, $nullToMysqlNull = false ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ( $fields as $field ) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        }
        else {
            $output[] = $field;
        }
    }

    return implode( $delimiter, $output );
}

function commenceParVoyelle($mot) {
	$c = substr($mot,0, 1);

	$vowels =
	    'aàáâãāăȧäảåǎȁąạḁẚầấẫẩằắẵẳǡǟǻậặæǽǣ' .
	    'AÀÁÂÃĀĂȦÄẢÅǍȀȂĄẠḀẦẤẪẨẰẮẴẲǠǞǺẬẶÆǼǢ' .
	    'EÈÉÊẼĒĔĖËẺĚȄȆẸȨĘḘḚỀẾỄỂḔḖỆḜ' .
	    'eèéêẽēĕėëẻěȅȇẹȩęḙḛềếễểḕḗệḝ' .
	    'IÌÍÎĨĪĬİÏỈǏỊĮȈȊḬḮ' .
	    'iìíîĩīĭıïỉǐịįȉȋḭḯ' .
	    'OÒÓÔÕŌŎȮÖỎŐǑȌȎƠǪỌØỒỐỖỔȰȪȬṌṐṒỜỚỠỞỢǬỘǾŒ' .
	    'oòóôõōŏȯöỏőǒȍȏơǫọøồốỗổȱȫȭṍṏṑṓờớỡởợǭộǿœ' .
	    'UÙÚÛŨŪŬÜỦŮŰǓȔȖƯỤṲŲṶṴṸṺǛǗǕǙỪỨỮỬỰ' .
	    'uùúûũūŭüủůűǔȕȗưụṳųṷṵṹṻǖǜǘǖǚừứữửự'
	;	
	if(strstr($vowels, $c)===false) {
		return false;
	} else {
		return true;
	}
}
function isLocal() {
	return strstr($_SERVER['HTTP_HOST'], '.local')!==false;
}
function isSocialSite() {
	return false;
	if (strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") !== false) {
		return true;
	}	

	if( strpos($_SERVER['HTTP_USER_AGENT'], 'Twitterbot') !== false ) {
		return true;
	}

	return false;
}

function phrase_temps($seconds) {
	$hours = floor($seconds / 3600);
	$mins = floor($seconds / 60 % 60);
	$secs = floor($seconds % 60);	
	if($hours) {
		$ret = pluriel($hours,'heure').($mins ? ' et '.pluriel($mins,'minute') : '');
	} elseif($mins) {
		$ret = pluriel($mins,'min').'.'.($secs ? ' '.pluriel($secs,'seconde') : '');
	} else {
		$ret = pluriel($secs,'seconde');
	}
	return $ret;
}

function pluriel($n,$mot) {
	if($n>1) {
		$mot.='s';
	}
	return $n.' '.$mot;
}
function freshurl($file) {
	$path = get_template_directory().'/'.$file;
	$url = get_template_directory_uri().'/'.$file.'?'.filemtime($path);
	return $url;
}
function load_file($file,$class=false) {
	if($class=='return') {
		$class='';
		$return=true;
	} else {
		$return=false;
	}
	if(is_array($file)) {
		foreach($file as $f) {
			load_file($f);
		}
	} else {
		$url = freshurl($file);
		if(strstr($url, '.css')!==false) {
		    $ret= '<link href="'.$url.'" rel="stylesheet">';
		} else if(strstr($url, '.js')!==false) {
			$ret='<script src="'.$url.'"></script>';
		} else if(strstr($url, '.jpg')!==false || strstr($url, '.png')!==false || strstr($url, '.gif')!==false) {
			$ret='<img '.($class ? 'class="'.$class.'"' : '').' src="'.$url.'">';
		}
		$ret.=PHP_EOL;
		if($return) {
			return $ret;
		} else {
			echo $ret;
		}
	}
}

function _is_admin() {
	return false;
	return current_user_can( 'manage_options' );
}
function _is_user() {
	return current_user_can( 'subscriber' );
}
function _is_logged() {
	if(_is_user() || _is_admin()) {
		return wp_get_current_user();
	}
}
function logo_kagibi(){
	echo '<img src="'.get_template_directory_uri().'/images/logo_kagibi_slogan_white.png">';
//	readfile(get_template_directory().'/images/logo.svg');	
}
function chemin_site() {
	return realpath(dirname(__FILE__).'/../../../../');
}
function check_tag($tag, $post=false) {
	if(!$post) {
		global $post;
	}
	return has_tag( $tag, $post );
}

function get_post_custom_value($key,$pid=false){
	global $post;
	if(!$pid){
		$pid = $post->ID;
	}
	if(list($ret) = get_post_custom_values($key,$pid)) {
		return $ret;
	} else {
		return false;
	}
}
function shuffle_assoc(&$array) {
	$keys = array_keys($array);

	shuffle($keys);

	foreach($keys as $key) {
		$new[$key] = $array[$key];
	}

	$array = $new;

	return true;
}

function get_current_loop_index($val=false){
	global $wp_query, $loop;
	if(!isset($loop)) {
		$loop = $wp_query;
	}
	return $loop->current_post;
}

function the_loop_index($val=false){
	if(!isset($GLOBALS['loop_index'])){
		$GLOBALS['loop_index']=-1;
	}
	if($val!==false){
		$GLOBALS['loop_index']=$val-1;
	}
	return $GLOBALS['loop_index']++;
}

function get_loop_index($val=false){
	return isset($GLOBALS['loop_index']) ? $GLOBALS['loop_index'] : 0;
}
function toHtmlAttributes($params,$no=array()){
	if(!is_array($no)){
		$no = array($no);
	}
	$out='';
	if(is_array($params)) {
		foreach($params as $k=>$v){
			if(!is_array($v) && in_array($k, $no)===false){
				if($k!='class' || !empty($v)){
					$out.=' '.$k.'="'.htmlspecialchars($v).'"';
				}
			}
		}
	}
	return $out;
}
function strip_tags_empty($html,$ok=array(),$_tags=false){
	if(!is_array($ok)){
		$ok = array($ok);
	}
	$tags = array('p','a','b','i','small','span');
	if(is_array($_tags)){
		$tags = $tags + $_tags;
	}
	foreach($tags as $tag){
		if(in_array($tag, $ok)===false) {
			$pattern = "/<".$tag."[^>]*><\\/".$tag."[^>]*>/"; 
			$html = preg_replace($pattern, '', $html); 	
		}
	}
	return $html;
}
function strip_tags_specific($text,$tags){
	if(!is_array($tags)){
		$tags = array($tags);
	}
	foreach($tags as $tag){
		$text = preg_replace("/<\\/?" . $tag . "(.|\\s)*?>/",'',$text);
	}
	return $text;
}
/**
* getHtmlVal
* 
* getHtmlVal($debut,$fin,$d,$nb=1,$trim=true)
* 
* @todo phpDoc
* 
* @param $debut
* @param $fin
* @param $d
* @param $nb valeur par défaut : 1
* @param $trim valeur par défaut : true
* @param $ret_tags si true, on retourne le résultat agrémenté de $debut et $fin
* 
* @return ($out)
* 
*/
function getHtmlVal($debut='',$fin='',$d,$nb=1,$trim=true,$ret_tags=false) {
	if($debut) {
		$tmp = explode($debut,$d);
		unset($tmp[0]);
	} else {
		$tmp = array($d);
	}
	if($nb==1){
		if($debut) {
			$out = implode($debut,$tmp);	
		} else  {
			$out = $tmp[0];
		}
	} else {
		$cpt=1;
		foreach($tmp as $k=>$v){
			if($cpt==$nb){
				$out=$v;
			}
			$cpt++;
		}
	}
	if($fin) {
		list($out) = explode($fin,$out);
	}
	if($ret_tags) {
		$out = $debut.$out.$fin;
	} else {
		if($trim) {
			$out = trim($out);
		}
	}
	return ($out);
}

function spliton($split,$content){
	$list = explode($split,$content);
	if(isset($list[0]) && empty($list[0])) {
		unset($list[0]);
	}
	foreach($list as &$line){
		$line = $split.$line;
	}
	return $list;
}
function array_to_html_attributes($tab, $others=array()) {
	foreach($others as $k=>$v){
		if(isset($tab[$k])){
			$tab[$k].=' '.$v;
		}
	}
	$out = '';
	foreach($tab as $k=>$v){
		$out.=' '.$k.'="'.htmlentities($v).'"';
	}
	return trim($out);
}
function check($var,$proof=false,$default=false,$post=false){
	if(php_sapi_name() == 'cli'){
		global $argv;
		foreach($argv as $argument){
			if($argument == $var){
				return true;
			} else {
				list($k) = explode('=',$argument);
				if($k == $var){
					return substr($argument,strlen($k)+1);
				}
			}
		}
		return false;
	} else {
		$tab = $post ? $_POST : $_GET;
		if(isset($tab[$var])){
			if($proof){
				eval('$ret = '.$proof.'($tab[$var]);');
				return $ret;
			} else {
				return $tab[$var];
			}
		} else {
			if(!$post){
				return check($var,$proof,$default,true);
			} else {
				return $default;
			}
		}
	}
}

function wget($file,$delay=3600){
	if(!is_numeric($delay)){
		$delay = strtotime($delay);
	}
	$tmpfile = SOCIETY_TMP.md5($file);
	$delta = time() - @filemtime($tmpfile);
	if(!file_exists($tmpfile) || ($delta > $delay)){
		$content = file_get_contents($file);
		file_put_contents($tmpfile, $content);
		return $content;
	} else {
		return file_get_contents($tmpfile);
	}
}

function normalize_path($path){
	return str_replace('\\', '/', $path);
}
/**
* sinon
* 
* sinon()
* 
* @todo phpDoc
* 
* @return val
* 
*/
function sinon(){
	$ret=false;
	$args = func_get_args();
	$data = $args[0];
	unset($args[0]);
	$empty=false;
	foreach($args as $k=>$v) {
		if(strstr($v,':')) {
			list($v,$len) = explode(':',$v);
			if($v == 'default'){
				$ret = $len;
				$v = $len;
				$len = false;
			}
			if($v == 'empty'){
				$empty=true;
				$v = $len;
				$len=false;
			}
		} else {
			$len = false;
		}
		if($empty){
			if(is_array($data) && isset($data[$v]) && !empty($data[$v])) {
				return $data[$v];
			}
		} else {
			if(is_array($data) && isset($data[$v]) && $data[$v] !== false) {
				return $len ? couper($data[$v],$len) : $data[$v];
			}
		}
	}
	
	return $ret;
}


/**
* couper
* 
* couper($texte, $taille=50, $suite = ' ...')
* 
* @todo phpDoc
* 
* @param $texte
* @param  $taille valeur par défaut : 50
* @param  $suite  valeur par défaut :  ' ...'
* 
* @return ''
* 
*/
function couper($texte, $taille=50, $suite = ' ...') {
	if (!($length=strlen($texte)) OR $taille <= 0) return '';
	$offset = 400 + 2*$taille;
	while ($offset<$length
		AND strlen(preg_replace(",<[^>]+>,Uims","",substr($texte,0,$offset)))<$taille)
		$offset = 2*$offset;
	if (	$offset<$length
		&& ($p_tag_ouvrant = strpos($texte,'<',$offset))!==NULL){
		$p_tag_fermant = strpos($texte,'>',$offset);
	if ($p_tag_fermant<$p_tag_ouvrant)
			$offset = $p_tag_fermant+1; // prolonger la coupe jusqu'au tag fermant suivant eventuel
	}
	$texte = substr($texte, 0, $offset); /* eviter de travailler sur 10ko pour extraire 150 caracteres */

	// on utilise les \r pour passer entre les gouttes
	$texte = str_replace("\r\n", "\n", $texte);
	$texte = str_replace("\r", "\n", $texte);

	// sauts de ligne et paragraphes
	$texte = preg_replace("/\n\n+/", "\r", $texte);
	$texte = preg_replace("/<(p|br)( [^>]*)?".">/", "\r", $texte);

	// supprimer les traits, lignes etc
	$texte = preg_replace("/(^|\r|\n)(-[-#\*]*|_ )/", "\r", $texte);

	// supprimer les tags
	$texte = strip_tags($texte);
	$texte = trim(str_replace("\n"," ", $texte));
	$texte .= "\n";	// marquer la fin


	// couper au mot precedent
	$long = substr($texte, 0, max($taille-4,1));
	$u = 'u';
	$court = preg_replace("/([^\s][\s]+)[^\s]*\n?$/".$u, "\\1", $long);
	$points = $suite;

	// trop court ? ne pas faire de (...)
	if (strlen($court) < max(0.75 * $taille,2)) {
//		$points = '';
		$long = substr($texte, 0, $taille);
		$texte = preg_replace("/([^\s][\s]+)[^\s]*\n?$/".$u, "\\1", $long);
		// encore trop court ? couper au caractere
		if (strlen($texte) < 0.75 * $taille)
			$texte = $long;
	} else
	$texte = $court;

	if (strpos($texte, "\n"))	// la fin est encore la : c'est qu'on n'a pas de texte de suite
	$points = '';

	// remettre les paragraphes
	$texte = preg_replace("/\r+/", "\n\n", $texte);

	// supprimer l'eventuelle entite finale mal coupee
	$texte = preg_replace('/&#?[a-z0-9]*$/S', '', $texte);

	return quote_amp(trim($texte)).$points;
}

/**
* quote_amp
* 
* quote_amp($u)
* 
* @todo phpDoc
* 
* @param $u
* 
* @return val
* 
*/
function quote_amp($u) {
	return preg_replace(
		"/&(?![a-z]{0,4}\w{2,3};|#x?[0-9a-f]{2,5};)/i",
		"&amp;",$u);
}

function addParamToUrl($url, $varName, $value)
{
    // is there already an ?
    if (strpos($url, "?"))
    {
        $url .= "&" . $varName . "=" . $value; 
    }
    else
    {
        $url .= "?" . $varName . "=" . $value;
    }
    return $url;
}