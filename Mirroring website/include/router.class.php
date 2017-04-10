<?php
/*
 * 路由表系统
 * 杨海涛
 *
 */
class Router{
    protected $pathlist=array();
    protected $callbackfuncs=array();
    protected $methods=array();
    protected $domainlist=array();

    public function __construct(){
        $this->domain = !empty($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:"";
        $this->path = !empty($_SERVER["REQUEST_URI"])?$_SERVER["REQUEST_URI"]:(!empty($_SERVER["HTTP_REQUEST_URI"])?$_SERVER["HTTP_REQUEST_URI"]:"");
        $this->method = !empty($_SERVER["REQUEST_METHOD"])?$_SERVER["REQUEST_METHOD"]:(!empty($_SERVER["HTTP_REQUEST_METHOD"])?$_SERVER["HTTP_REQUEST_METHOD"]:"");
        if($this->domain == '' || $this->path == '' || $this->method == ''){
            throw new Exception("Environment detection failed!");
        }
    }

    //添加路由系统
    //path支持正则表达，正则用()表示，传递的时候，一个()为一个参数
    public function add_route($path,$callback,$method="GET",$domain=".*"){
        array_push($this->pathlist,$path);
        array_push($this->callbackfuncs,$callback);
        array_push($this->methods,$method);
        array_push($this->domainlist,$domain);
    }
    //找出最符合系统要求的路由表
    protected function get_routeway(){
        $routers=array();
        for($i=0;$i<count($this->pathlist);$i++){
            $path=addcslashes($this->pathlist[$i],"/");
            $domain=addcslashes($this->domainlist[$i],"/");
            $method=preg_split("/[\W]/",$this->methods[$i]);
            //如果当前包含URL的路由表中，不支持当前请求方法，则进行下一个比较   
            if(!in_array($this->method,$method)){
                continue;
            }
            //如果当前URL路由表中，域名不匹配，则进行下一个比较
            if(!preg_match("/^$domain/i",$this->domain)){
                continue;
            }
            //如果路由不匹配，则进行下一个比较
            if(!preg_match("/^$path/",$this->path)){
                continue;
            }
            array_push($routers,$i);
        }
        //如果有多个符合要求，则选中最符合要求的那个(URL最长)
        $len = strlen($this->pathlist[$routers[0]]);
        foreach($routers as $offset){
            $len = ($len<strlen($this->pathlist[$routers[$offset]]))?strlen($this->pathlist[$routers[$offset]]):$len;
        }
        return $offset;
    }

    //路由运行，需要找到那个最匹配的规则，然后运行
    //最重要的要确定参数的个数，因为参数需要传递到回调函数中
    //通过()个数获取参数个数
    public function run(){
       $key = $this->get_routeway();
       $pathreg = addcslashes($this->pathlist[$key],"/");
       $domainreg = addcslashes($this->domainlist[$key],"/");
       preg_match("/$pathreg/",$);
    }
}
