<?php
/*
 * COOKIE操作
 * 杨海涛 2017年3月7日
 */
class COOKIE{
    private $protocols;
    private $domain;
    private $path;
    private $filename;
    public $urire="/(https?):\/\/([^\/]*)(.*)$/i";
    public function __construct($url){
        preg_match($this->urire,$url,$match);
        if(empty($match)) throw new Exception("$url is Not a legal URL");
        $this->protocols=$match[1];
        $this->domain=$match[2];
    }
    
    public function take(SQLite3 $db){
        $db->getone("select from cookie where domain = $this->domain and time() < time");
    
    
    }

    public function storage($jsondata){
        $obj = json_decode($jsondata);
        foreach($obj as $data){
        
        }
    }

    






}

/*
require_once("spider.class.php");
require_once("httpparser.class.php");
//1,初始化蜘蛛
$spider = new Spider("https://www.baidu.com/");
//2,看是否有cookie
//3,爬行，获取内容和cookie，并保存


$rawhttp = $spider->crawl();

$http = new HTTPParser($rawhttp);

echo $http->get_cookie();
*/
$a = new COOKIE("https://www.hitoy.org/super/sfasaf.html");
print_r($a);
