<?php
/*
 * 首页文件
 * 杨海涛 2017年3月7日
 *
 * permalink支持的关键词
 * category_name
 * post_name
 */
require_once("./init.php");
require_once(LIBPATH."functions.php");
/*
 * 以下是当前运行环境几个全局变量
 * domain
 * uri
 * webname
 * permalink
 * domainid
 * catagorys array
 * templateurl
 * indextemplate
 */
$categorys=array();
$domain = $_SERVER["HTTP_HOST"];
$uri = isset($_SERVER["REQUEST_URI"])?$_SERVER["REQUEST_URI"]:$_SERVER["HTTP_REQUEST_URI"];
$tmp = $configdb->query("select * from domains where domain =\"$domain\"");
$tmp2 = $tmp->fetchArray();
//if(empty($web)) error('404');
$webname = $tmp2['title'];
$permalink = $tmp2['permalink'];
$indextemplate = $tmp2['template'];
$domainid = $tmp2['id'];
$tmp3 = $configdb->query("select * from category where domainid = \"$domainid\"");
while(($tmp=$tmp3->fetchArray(SQLITE3_ASSOC))!=false){
    array_push($categorys,$tmp);
}


$templateurl="//".$domain."/template/";


//开始判断页面类型
if($uri == "/"){
    //首页
    require_once("./template/$indextemplate");
}else if(preg_match("/^\/sitemap(-\d*)?\.xml$/i",$uri)){
    //sitemap
    preg_match("/^\/sitemap(-(\d*))?\.xml$/i",$uri,$match);
    $page = !empty($match[2])?$match[2]:0;
    require_once("./sitemap.php");
}else if(preg_match("/^\/category\/([^\/]*)/i",$uri)){
    //分类页面
    preg_match("/^\/category\/([^\/]*)(\/page\/(\d*))?/i",$uri,$match);
    $cate_name = $match[1];
    if(empty($match[3])){
        $page = 1;
    }else{
        $page = $match[3];
    }
    $offset = ($page-1)*ListPageSize;
    //1. 获取列表信息
    $tmp = $articledb->query("select * from article where domainid =$domainid and cate_name = \"$cate_name\" limit $offset,".ListPageSize);
    $pagelist=array();
    while(($tmp2=$tmp->fetchArray(1))!==false){
        array_push($pagelist,$tmp2);
    }
    if(empty($pagelist)){
        error(404);
    }
    //2. 获取页面总数
    $tmp = $articledb->query("select count(id) as pages from article where domainid =$domainid and cate_name = \"$cate_name\"");
    $pages= ceil(($tmp->fetchArray(1)['pages'])/ListPageSize);

    //3. 获取配置信息
    $tmp = $configdb->query("select categorytemplate from category where domainid =$domainid and name = \"$cate_name\"");
    $tmp3 = $tmp->fetchArray();
    //如果为空，则分类不存在，返回404
    if(empty($tmp3)) error(404);
    //获取当前分类的模板，并调用
    $categorytemplate = $tmp3['categorytemplate'];
    require_once("./template/$categorytemplate");
}else if(preg_match("/^\/page\/([^\/]*)/i",$uri)){
    //单页
    preg_match("/^\/page\/([^\/]*)/i",$uri,$match);
    //如果不存在单页，则返回404
    if(file_exists("./template/$match[1].php")==false){
        error(404);
    }
    require_once("./template/$match[1].php");
}else{
    //文章页
    //通过固定链接方式和URI，提取当前文章，然后渲染
    //文章页的固定链接暂时固定为post_name.html
    /*
    if(strpos($permalink,"%category_name%") !==false){
        //固定链接中有分类的情况
    }
     */
    //1.获取文章情况
    preg_match("/\/([^\.]*)\.html/i",$uri,$match);
    if(empty($match[1])){
        error(404);
    }

    $tmp=$articledb->query("select * from article where slug = \"$match[1]\" and domainid = \"$domainid\"");
    $tmp2 = $tmp->fetchArray();
    if(empty($tmp2)){
        error(404);
    }
    //有内容则需要初始化文件系统
    $filesystem = new FileSystem();

    $post_title = $tmp2['title'];
    $post_cate_name = $tmp2['cate_name'];
    $post_slug = $tmp2['slug'];
    $post_keyword = $tmp2['keyword'];
    $post_tag = $tmp2['tag'];
    $post_excerpt = $tmp2['excerpt'];
    $post_content = $filesystem->read($tmp2['content']);
    //2. 获取这个页面的模板
    $tmp = $configdb->query("select singletemplate from category where name =\"$post_cate_name\" and domainid = \"$domainid\"");
    $tmp3 = $tmp->fetchArray();
    $singletemplate = $tmp3['singletemplate'];
    if(!file_exists("./template/$singletemplate")){
        error(500,'Template Not Found!');
     }
    //3. 渲染
    require_once("./template/$singletemplate");
}
