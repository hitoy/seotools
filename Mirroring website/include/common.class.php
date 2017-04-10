<?php
/*
 * 和网站相关的配置文件
 */
require("../init.php");
class WebSite{
    public $title;
   
    public $permalink;
    public $indextemplate;
    public $categorys=array();
    //页面类型 首页，分类页面，内容页面 404
    public $pagetype;

    public $domain;
    public $uri;

    public function __construct($domain,$uri){
        $this->domian=$domian;
        $this->uri = $uri;
        global $configdb;
        $res = $configdb->query("select * from domains where domain = \"$domain\"");
        $web = ($res->fetchArray());
        if(empty($web)) {
            throw new Exception("WebSite Not Exists");
            exit();
        }
        $this->title = $web['title'];
        $this->permalink = $web['permalink'];
        $this->indextemplate = $web['template'];
        $domainid = $web['id'];
        $res = $configdb->query("select * from category where domainid = \"$domainid\"");
        while(($tmp=$res->fetchArray(SQLITE3_ASSOC))!=false){
            array_push($this->categorys,$tmp);
        }
    }

}

$a = new WebSite($_SERVER['HTTP_HOST'],"/category/case");
print_r($a);
