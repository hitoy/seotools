<?php
/*
Plugin Name: Ixquick Search Result
Plugin URI: https://www.hitoy.org/
Description: Ixquick Search Result
Version: 1.0.0
Author: Hito
Author URI: https://www.hitoy.org/
Text Domain: super_static_cache
Domain Path: /languages/
License: GPL2
 */



function array_urlencode($array){
    $strings="";
    foreach($array as $k=>$v){
        $strings.="&".urlencode($k)."=".urlencode($v);
    }
    return $strings;
}

function get_result($strings){
    $content="";
    preg_match_all("/<li id='result\d'>[\s\S]*?<\/li>/i",$strings,$match);
    foreach($match[0] as $list){
        preg_match("/<span class=\"result_url_heading\">(.*?)<\/span>/i",$list,$m);
        preg_match("/<p class='desc clk'>(.*?)<\/p>/i",$list,$d);
        $title = $m[1];
        $pos = stripos($title,"-")?stripos($title,"-"):strlen($title);
        $title = substr($title,0,$pos);
        $p = $d[1];
        if($title=="" || $p=="") continue;
        $content.="<h2>".$title."</h2>\r\n<p>".$p."</p>\r\n";
    }
    return $content;
}
function get_next_page_data($strings){
    global $url;
    preg_match("/<form action=\"(\S*)\" id=\"pnform\" name=\"pnform\"[^>]*>([\s\S]*?)<\/form>/i",$strings,$match);
    $url = $match[1];
    preg_match_all("/name=\"([^\"]*)\" value=\"([^\"]*)\"/i",$match[2],$m);
    foreach($m[1] as $k=>$v){
        $postdata[$v]=$m[2][$k];
    }
    return $postdata;
}

function curl($url,$data=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(!empty($data)){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array_urlencode($data));
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function add_content(){
    $title = get_the_title();
    $url = "https://www.ixquick.eu/do/asearch";
    $postdata = array("cat"=>"web","cmd"=>"process_search","language"=>"english","engine0"=>"v1all","query"=>$title,"abp"=>"-1","t"=>"air","nj"=>"0","hmb"=>1,"pg"=>4);
    $content = "";
    $i=0;
    $html = curl($url,$postdata);
    return get_result($html).get_the_content();
}
add_filter("the_content","add_content");
