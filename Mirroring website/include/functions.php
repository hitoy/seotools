<?php
/*
 * 函数文件在此
 *
 */
function error($httpcode,$note="",$errortempalte='error.php'){
    $httperror=array("403"=>"HTTP/1.1 Forbidden","404"=>"HTTP/1.1 404 Not Found","410"=>"HTTP/1.1 410 Gone","500"=>"HTTP/1.1 Internal Server Error");
    $defaultnote=array("403"=>"You do not have permission to access this resource","404"=>"Resources You Visist Can not Find","410"=>"Resources are permanently removed","500"=>"Server error, please try again later");
    if( !in_array($httpcode,array_keys($httperror)) ){
        throw new Exception("This error code is not supported!");
    }
    header($httperror[$httpcode]);
    if($note=="") $note=$defaultnote[$httpcode];
    require("./template/error.php");
    die();
}

function rand_article_list($num,$category){
    global $articledb,$domainid;
    $tmp = $articledb->query("select * from article where domainid = \"$domainid\" and cate_name =\"$category\" order by random() desc limit 0,$num");
    $returned=array();
    while(($tmp2 = $tmp->fetchArray(1))!=false){
        array_push($returned,$tmp2);
    }
    return $returned;
}

function recent_article_list($num,$category){
    global $articledb,$domainid;
    $tmp = $articledb->query("select * from article where domainid = \"$domainid\" and cate_name =\"$category\" order by id desc limit 0,$num");
    $returned=array();
    while(($tmp2 = $tmp->fetchArray(1))!=false){
        array_push($returned,$tmp2);
    }
    return $returned;
}

function category_page($cate_name,$thispageno,$totalpage,$showcount=5,$firstnote="First Page",$lastnote="Last Page"){
    if($totalpage == 1) return '';
    $start = ($thispageno-floor($showcount/2))<1?1:$thispageno-floor($showcount/2);
    if($start == 2){
        $html="<li><a href=\"/category/$cate_name\">1</a></li>";
    }else if($start>2){
        $html="<li><a href=\"/category/$cate_name\">$firstnote</a></li>";
    }else{
        $html='';
    }
    for($i=0;$i<$showcount;$i++){
        if($start>$totalpage) break;
        if($start==$thispageno){
            $html.="<li class=\"thispage\">$start</li>";
        }else{
            $html.="<li><a href=\"/category/$cate_name/page/$start\">$start</a></li>";
        }
        $start++;
    }
    if($totalpage-$start>0){
        $html.="<li><a href=\"/category/$cate_name/page/$totalpage\">$lastnote</a></li>";
    }
    return $html;
}
