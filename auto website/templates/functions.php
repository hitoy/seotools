<?php
function get_sidebar(){
    require_once(dirname(__FILE__)."/sidebar.php");
}
function get_header(){
    require_once(dirname(__FILE__)."/header.php");
}
function get_comment(){
    require_once(dirname(__FILE__)."/comment.php");
}
function get_footer(){
    require_once(dirname(__FILE__)."/footer.php");
}

function is_single(){
    global $router;
    if($router->type=="single"){
        return true;
    }
    return false;
}

//by hito
function showimglist($title,$content){
    $content=trim($content);
    if(substr($content,0,4) != '<h2>') return $content;
    preg_match_all("/<h2>([\s\S\n]*?)<\/p>/i",$content,$match); 
    if(!$match[0]) return $content;
    $content="";  
    foreach($match[0] as $t){
        preg_match("/<h\d>(.*?)<\/h\d>/i",$t,$m);
        $alt=strtolower($m[1]);
        $li="<li>".show_rand_img($alt,1,"/uploads/").$t."</li>\r\n";
        $content = $content.$li;
    }
    return "<ul class=\"list\">\r\n".$content."</ul>\r\n";
}

function show_rand_img($alt="",$num=1,$dir="/uploads/"){
    $document_root=$_SERVER["DOCUMENT_ROOT"];
    $imgdir=$document_root.$dir;
    if(!file_exists($imgdir)) exit("图片目录不存在");
    $imgarr=scandir($imgdir);
    if($num>count($imgarr)) $num=count($imgarr);
    $re="";
    for($i=0;$i<$num;$i++){
        $s=rand(0,count($imgarr));
        if(!is_file($imgdir.$imgarr[$s])){$i--;continue;}
        $re .= "<img src=\"$dir$imgarr[$s]\" alt=\"$alt\" class=\"imglist\"/>";
    }
    return $re;
}

//show totle artice
function showarctile($alt,$content,$imgoffset=4,$imgdir="/uploads/"){
    $content=trim($content);
    if(substr($content,0,4) != '<h2>') return $content;
    preg_match_all("/<h2>([\s\S\n]*?)<\/p>/i",$content,$match); 
    if(!$match[0]) return $content;
    $content = preg_replace("/<h2>[^<>]*<\/h2>/i","",$content);
    $arr=explode('</p>',$content);
    if(count($arr)>$imgoffset){
        $arr[$imgoffset]=$arr[$imgoffset]."\n".show_rand_img($alt,1,$imgdir)."\n";
    }else if(count($arr)>1){
        $arr[0]=$arr[0]."\n".show_rand_img($alt,1,$imgdir)."\n";
    }
    $content=implode('',$arr);
    $content=strip_tags($content,'<img>');
    $content=str_replace("...","",$content);
    return $content;
}
//show just p
function showp($content){
    $content=trim($content);
    if(substr($content,0,4) != '<h2>') return $content;
    preg_match_all("/<h2>([\s\S\n]*?)<\/p>/i",$content,$match); 
    if(!$match[0]) return $content;
    $content = preg_replace("/<h2>[^<>]*<\/h2>/i","",$content);
    $content = str_replace("...","",$content);
    return $content;
}



function get_cache($html){
    $filename=ABPATH."/cache/".urldecode($_SERVER[REQUEST_URI]);
    if(!file_exists($filename)){
        file_put_contents($filename,$html,LOCK_EX);
    }
    return trim($html);
}
//缓存功能
if(is_single()){
    ob_start('get_cache');
}
