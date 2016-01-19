<?php
define("ABPATH",str_replace("\\","/",dirname(__FILE__))."/");
require_once("./config.php");
require_once("./lib/list.class.php");
require_once("./lib/content.class.php");
require_once("./lib/router.class.php");

$uri=$_SERVER['REQUEST_URI'];
$router=new Router($uri);

function getcatename(){
    $uri=$_SERVER['REQUEST_URI'];
    global $router;
    if($router->type == "category"){
        preg_match("/\/category\/(.*?)\/(\d*)/i",$uri,$match);
        $page=!(empty($match[2]))?$match[2]:1;
        return array("catname"=>str_replace("-"," ",urldecode($match[1])),"page"=>$page);
    }
}

function getpostname(){
    $uri=$_SERVER['REQUEST_URI'];
    global $router;
    if($router->type == "single"){
        preg_match("/^\/(.*?)\.html/i",$uri,$match);
        return str_replace("-"," ",urldecode($match[1])); 
    }
}

$list=new ContentList();
