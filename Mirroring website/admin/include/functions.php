<?php
$linkspider = new Spider();
//linkspider 为链接爬行蜘蛛
$contentspider = new Spider();
//contenspider 为内容爬行蜘蛛
/*
 *通过指定的网址和条件获取页面链接
 htmlstart htmlend 为字符串
 urlmustcontain urlnotcontain 为"|"连接的数组字符串
 */
function getlinks($url,$htmlstart,$htmlend,$urlmustcontain,$urlnotcontain){
    global $linkspider;
    $mustcontain = array_filter(explode("|",$urlmustcontain));
    $notcontain = array_filter(explode("|",$urlnotcontain));
    /*
     * 如果没有设置二级地址提取规则，则代表提交的是最终的网址
     */
    if($htmlstart === "" && $htmlend === "" && empty($mustcontain) && empty($notcontain)){
        return array($url);
    }
    //最终的返回url列表
    $urls=array();
    $rawdata = $linkspider->crawl($url);
    $httpparser = new HTTPParser($rawdata);
    $html = $httpparser->get_html();
    $html = substr($html,stripos($html,$htmlstart)+strlen($htmlstart));
    $html = substr($html,0,stripos($html,$htmlend));
    preg_match_all("/<a.*href=[\"\']?(https?[^\s\'\"\>\#\?]*)[\"\']?/i",$html,$matches);

    //把原始的url也需要插入到urls列表中
    array_push($urls,$url);
    //把相对地址换成绝对地址
    foreach(array_unique($matches[1]) as $link){
        $url = relative2absoluteurl($url,$link);
        array_push($urls,$url);
    }
    //再次去重
    $urls = array_unique($urls);
    $i=0;
    foreach($urls as $url){
        //必须不包含的是任何一个都不能包含
        //只要包含了，直接把网址删除
        //直接跳出检测，不需要检测下面关键词是否包含在URL中
        foreach($notcontain as $not){
            if(stripos($url,$not)!==false){
                unset($urls[$i]);
                break;
            }
        }
        //必须包含是包含任何一个即可
        //只要包含任何一个，直接跳出检测
        foreach($mustcontain as $must){
            unset($urls[$i]);
            if(stripos($url,$must)!==false){
                $urls[$i] = $url;
                break;
            }
        }
        $i++;
    }
    return array_values($urls);
}

/*
 *根据网址和相对地址获取绝对链接地址
 */
function relative2absoluteurl($currenturl,$targeturl){
    if(substr($targeturl,0,4) == 'http' || substr($targeturl,0,2) == '//') return $targeturl;
    if(substr($currenturl,0,4) !== 'http') return false;
    preg_match("/(https?:\/\/)([^\/]*)\/(.*)\//i",$currenturl,$match);
    $protocol = $match[1];
    $domain = $match[2];
    $path = $match[3];
    if(substr($targeturl,0,1)=='/') return $protocol.$domain.$targeturl;
    if(substr($targeturl,0,2)=='./') return $protocol.$domain.$path.substr($targeturl,1);
    preg_match("/((\.\.\/)*)(.*)/i",$targeturl,$match);
    $i = strlen($match[1])/3;
    while($i>0){
        $path=substr($path,0,strripos($path,"/"));
        $i--;
    }
    return $protocol.$domain.'/'.$path.'/'.$match[3];
}

/*
 * 获取页面内容，并根据内容模型切分成二维数组
 * 如果不满足内容字段要求，则
 */
function getcontentarr($url,$titlestart,$titleend,$contentstart,$contentend,$loopboolean=false,$keywordstart=false,$keywordend=false,$tagstart=false,$tagend=false){
    global $contentspider;
    $rawdata = $contentspider->crawl($url);
    $httpparser=new HTTPParser($rawdata);
    $html = $httpparser->get_html();
    //title
    $title = "";
    $titlestartpos = strpos($html,$titlestart)+strlen($titlestart);
    $titleendpos = strpos($html,$titleend);
    $title = substr($html,$titlestartpos,($titleendpos-$titlestartpos));
    //keyword
    $keyword = "" ;
    if($keywordstart!==false && $keywordend!==false){
        $keywordstartpos = strpos($html,$keywordstart)+strlen($keywordstart);
        $keywordendpos = strpos($html,$keywordend);
        $keyword = substr($html,$keywordstartpos,($keywordendpos-$keywordstartpos));
    }
    $tag = "" ;
    if($tagstart!==false && $tagend!==false){
        $tagstartpos = strpos($html,$tagstart)+strlen($tagstart);
        $tagendpos = strpos($html,$tagend);
        $tag = substr($html,$tagstartpos,($tagendpos-$tagstartpos));
    }
    //content
    $content = "";
    do{
        $contentstartpos = strpos($html,$contentstart)+strlen($contentstart);
        $contentendpos = strpos($html,$contentend);
        if($contentstartpos===false){
            $loop=false;
        }else if($contentendpos === false){
            $contentendpos = strlen($html);
        }
        $content .= substr($html,$contentstartpos,($contentendpos-$contentstartpos));
        $html = substr($html,$contentendpos);
    }while($loopboolean);
    return array("title"=>$title,"content"=>$content,"keyword"=>$keyword,"tag"=>$tag);
}
