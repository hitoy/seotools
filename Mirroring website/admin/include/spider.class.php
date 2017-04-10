<?php
/*
 * PHP爬虫
 * 作者杨海涛 2017年3月6日
 */
class Spider{
    public static $DNS_CACHE_TIME = 3600;
    #请求超时时间
    public static $timeout=5;
    #请求的URL
    protected $_uri;
    #请求方法 GET POST HEAD DELETE...
    protected $_method = "GET";
    #cookie
    protected $_cookie;
    #referer来源
    protected $_referer = "";
    #用户代理
    protected $_useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
    #普通HTTP头部信息
    protected $_httpheader=array("Accept"=>"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8","Accept-Encoding"=>"gzip,deflate","Accept-Language"=>"zh-CN,zh;q=0.8","Cache-Control"=>"max-age:0","Connection"=>"keep-alive");
    #HTTP代理
    protected $_proxy=array();
    #POST DATA
    private $_post;

    public function __construct($useragent="Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",$timeout=5){
        $this->_useragent=$useragent;
        self::$timeout=$timeout;
    }

    public function add_header($key,$value){
        $this->_httpheader[$key] = $value;
    }
    public function rm_header($key){
        if(array_key_exists($key)){
           $this->_httpheader=delete_array($this->_httpheader,$key);
           return true;
        }
        return false;
    }

    public function set_useragent($useragent){
        $this->_useragent=$useragent;
    }

    public function setmethod($method){
        $this->_method=$method;
    }

    public function add_post(array $data){
        $this->_post=$data;
        $this->_method="POST";
    }

    public function add_proxy($proxy){
        $this->_proxy=$proxy;
    }


    protected function curlhandler($showheader=true){
        if(!function_exists("curl_init")) throw new Exception("Error,Curl libray does not exist!");
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->_uri);
        //curl_setopt($ch,CURLOPT_CONNECTIONTIMEOUT,5);
        //curl_setopt($ch,CURLOPT_DNS_CACHE_TIMEOUT,self::DNS_CACHE_TIME);
        curl_setopt($ch,CURLOPT_COOKIE,$this->_cookie);
        curl_setopt($ch,CURLOPT_USERAGENT,$this->_useragent);
        curl_setopt($ch,CURLOPT_REFERER,$this->_referer);
        curl_setopt($ch,CURLOPT_HTTPHEADER,header_change($this->_httpheader));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        if($showheader==true){
            curl_setopt($ch,CURLOPT_HEADER,1);
        }
        if(!empty($this->_post)){
            curl_setopt($ch,CURL_POST,1);
            curl_setopt($ch,CURLOPT_POSTFILEDS,$this->_post);
        }
        return $ch;
    }

    public function set_cookie(array $data){
        $str='';
        foreach($data as $k=>$v){
            $str.=urlencode($k)."=".urlencode($v)."&";
        }
        $this->_cookie = trim($str,"&");
    }

    public function crawl($url,$showheader=true){
       $this->_uri=$url;
       $curlhandler = $this->curlhandler($showheader);
       $http =  curl_exec($curlhandler);
       curl_close($curlhandler);
       return $http;
    }
}

#HTTP中二维数组请求头变为1维数组
function header_change($array){
    $newarray=array();
    foreach($array as $k=>$v){
        array_push($newarray,"$k:$v");
    }
    return $newarray;
}
#删除二维数组中的某个元素
function delete_array($array,$key){
    $newarray=array();
    foreach($array as $k=>$v){
        if($k==$key) continue;   
        $newarray[$k]=$v;
    }
    return $newarray;
}
