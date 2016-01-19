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

//by hito
function showimglist($content){
	$content=trim($content);
	if(substr($content,0,4) != '<h2>') return $content;
	preg_match_all("/<h2>([\s\S\n]*?)<\/p>/i",$content,$match); 
	if(!$match[0]) return $content;
	$content="";  
	foreach($match[0] as $t){
		preg_match("/^([\s\S\n]*?)<\/h2>([\s\S\n]+)$/",$t,$cc);
		preg_match("/<h\d>(.*?)<\/h\d>/i",$t,$m);
		$alt=strtolower($m[1]);
		$li="<div>".$cc[1]."</h2>".show_rand_img($alt,1,"/images/").$cc[2]."</div>\r\n";
		$content = $content.$li;
	}
	return $content;
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
			$src="//".$_SERVER["HTTP_HOST"].$dir.$imgarr[$s];
        $re .= "<img src=\"$src\" alt=\"$alt\" class=\"imglist\"/>";
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
