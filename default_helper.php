<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function indonesian_date($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
    if (trim ($timestamp) == '')
    {
            $timestamp = time ();
    }
    elseif (!ctype_digit ($timestamp))
    {
        $timestamp = strtotime ($timestamp);
    }
	$space = ' ';
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace ("/S/", "", $date_format);
    $pattern = array (
        '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
        '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
        '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
        '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
        '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
        '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
        '/April/','/June/','/July/','/August/','/September/','/October/',
        '/November/','/December/',
    );
    $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
        'Jan ','Feb ','Mar ','Apr ','Mei ','Jun ','Jul ','Aug ','Sep ','Okt ','Nov ','Des ',
        'Januari','Februari','Maret','April','Juni','Juli','Agustus','September',
        'Oktober','November','Desember',
    );
    $date = date ($date_format, $timestamp);
    $date = preg_replace ($pattern, $replace, $date);
	
	if(empty($suffix)) 
		$space = '';
		
    $date = "{$date}{$space}{$suffix}";
    return $date;
}


function wordwarp($text,$length,$dot=false){
        $text = explode(" ",$text);
        $new_text = "";
        if(count($text) < $length){
            $length = count($text); 
        }

        for($i=0;$i<$length;$i++){
            $new_text .= $text[$i]." ";
        }
        if($dot==false){
            $new_text = $new_text;
        }else{
            $new_text = $new_text."...";
        }
        return $new_text;
}

function getRealIpAddr(){
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        
        }else{
        
          $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
}


function set_site_title($title='')
{
    if(!$title || $title==null)
        return;
    global $_site_title;
    $_site_title    = $title;
}

function set_meta_data($name='', $content='')
{
    if(!$name)
        return;
    
    global $_meta_data;
    if(!is_array($_meta_data))
        $_meta_data = array();
    $_meta_data[$name] = $content;
}

function send_mail($setting,$mail,$data){
	/* Format Array Setting
	 * $setting['host'] = your email host
	 * $setting['user'] = your email user
	 * $setting['pass'] = your email password
	 * $setting['port'] = your port email host
	 * $setting['from'] = Email address you used to send
	 * $setting['to'] = Email address, you want to send
	 * $setting['subject'] = Email Subject
	 */	 
	$CI = &get_instance(); 
	$CI->load->library('email');
	// Load $view ('name of view file'), $data (data you want to load in your email)
	$body = $CI->load->view($mail,$data='',TRUE);
	$config['protocol'] = 'smtp';
	$config['smtp_host'] = $setting['host'];
	if(isset($setting['user']) && !empty($setting['user'])){
		$config['smtp_user'] = $setting['user'];
		$config['smtp_pass'] = $setting['pass'];
	}
	$config['newline'] = "\r\n";
	$config['smtp_port'] = $setting['port'];
	$config['mailtype']  = 'html';

	$CI->email->initialize($config);
	$CI->email->from($setting['from']);
	$CI->email->to($setting['to']);
	$CI->email->subject($setting['subject']);
	$CI->email->message($body);
	if($CI->email->send()){
		$message = 'ok';
	}else{
		$message = $CI->email->print_debugger();
	}
	return $message;
}

function ismobile(){
	$CI = &get_instance(); 
	$CI->load->library('user_agent');
	if ($CI->agent->is_mobile())	{
	      return TRUE;
	}else{
	     return FALSE;
	}
}

function singledoublequote($string){
  if(substr_count($string, '"')>0){
    return "'".$string."'";
  }elseif(strpos($string, "'")>0){
    return '"'.$string.'"';
  }else{
    return '"'.$string.'"';
  }
}

function indonesian_currency($bilangan) {
	return number_format($bilangan,0,',','.');
}

function stripUnwantedTagsAndAttrs($html_str){
  $xml = new DOMDocument();
//Suppress warnings: proper error handling is beyond scope of example
  libxml_use_internal_errors(true);
//List the tags you want to allow here, NOTE you MUST allow html and body otherwise entire string will be cleared
  $allowed_tags = array("html", "body", "b", "br", "em", "hr", "i", "li", "ol", "p", "s", "span", "table", "tr", "td", "u", "ul");
//List the attributes you want to allow here
  $allowed_attrs = array ("class", "id", "style", "data-js-country");
  if (!strlen($html_str)){return false;}
  if ($xml->loadHTML($html_str, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){
    foreach ($xml->getElementsByTagName("*") as $tag){
      if (!in_array($tag->tagName, $allowed_tags)){
        $tag->parentNode->removeChild($tag);
      }else{
        foreach ($tag->attributes as $attr){
          if (!in_array($attr->nodeName, $allowed_attrs)){
            $tag->removeAttribute($attr->nodeName);
          }
        }
      }
    }
  }
  return $xml->saveHTML();
}
