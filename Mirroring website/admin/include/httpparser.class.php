<?php
/*
 * HTTP解析器，用来把HTTP返回的文本解析成对象
 * 杨海涛 2017年3月6日
 */

class HTTPParser{
    //原始HTTP数据
    public $rawdata;
    //原始HTTP头部
    public $rawheader;
    //HTTP version
    public $version;
    //HTTP status
    public $status;
    //HTML数据 rawhtml解压之前的HTML
    public $rawhtml;
    public $html;
    //二维数组HTTP头部信息
    public $header=array();
    //编码方式
    public $charset;
    //HTTP压缩方式
    public $encoding;
    //Cookie
    public $cookie=array();
    //HTTP正则
    protected $httpreg="/^HTTP\/(\S*)\s*([^\r]*)[\r\n]*(([^\r\n]*[\r\n])*)\r\n([\s\S]*$)/i";
    //http cookie正则
    protected $cookiereg="/(([^=\s]*)=([^:;]*))[;:]/i";
    public function __construct($rawdata){
        preg_match($this->httpreg,$rawdata,$match);
        if(empty($match)) throw new Exception("Not a HTTP Response!");
        $this->$rawdata=$rawdata;
        $this->version=$match[1];
        $this->status=$match[2];
        $this->rawheader=$match[3];
        $this->rawhtml=$match[5];
        $this->__init__();
    }

    //初始化
    protected function __init__(){
        $tmp=explode("\r\n",$this->rawheader);
        foreach($tmp as $list){
            //jump if empty
            if(empty($list)) continue;
            //split
            $key = trim(substr($list,0,strpos($list,":")));
            $value = trim(substr($list,strpos($list,":")+1));
            //cookie process
            //cookie format:array("data"=>array("key1"=>"value1","key2"=>"value2"...),"expires"=>'','max-age'=>'','path'=>"",'domain'=>'')
            $headercookie=array("data"=>array(),"expires"=>gmdate('D, d M Y H:i:s T'),"max-age"=>0,"path"=>NULL,"domain"=>NULL);
            if(strtolower($key)=='set-cookie'){
                preg_match_all($this->cookiereg,$value.";",$match);
                $i = 0;
                for($i=0;$i<count($match[1]);$i++){
                    $cookname = $match[2][$i];   
                    $cookvalue = $match[3][$i];
                    if($cookname == "expires" || $cookname == "max-age" || $cookname == "path" || $cookname == "domain"){
                        $headercookie[$cookname]=$cookvalue;
                    }else{
                        array_push($headercookie['data'],array($cookname=>$cookvalue));
                    }
                }
                array_push($this->cookie,$headercookie);
            }else{
                $this->header[$key]=$value;
            }
        }
        $this->encoding = isset($this->header['Content-Encoding'])?$this->header['Content-Encoding']:"";
        $this->charset = isset($this->header['Content-Type'])?substr($this->header['Content-Type'],strpos($this->header['Content-Type'],'=')+1):"";
    }

    public function get_html(){
        if($this->encoding=='gzip' && function_exists("gzdecode")){
            $this->html=gzdecode($this->rawhtml);
            return $this->html;
        }else if($this->encoding=='gzip' && !function_exists("gzdecode")){
           throw new Exception("System does not have a GZIP Libary, Please Install!");
        }else if($this->encoding=='' && function_exists("")){
            $this->html =  gzuncompress($this->rawhtml);
            return $this->html;
        }else if($this->encoding=='' && !function_exists("")){
            throw new Exception("System does not have a GZIP Libary, Please Install!");
        }else if($this->encoding==""){
            $this->html = $this->rawhtml;
            return $this->html;
        }
        throw new Exception("Unkown Content-Encoding, system exists!");
    }

    public function get_cookie(){
        return json_encode($this->cookie);
    }
}
