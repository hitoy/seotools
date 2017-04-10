<?php
/*
 * JOB类 根据设置生成需要采集的网址库
 *
 */
class JOB{
    public $urls;
    //通过HTML提取网址的分隔符
    public $htmlstart="";
    public $htmlend="";
    //通过链接地址提取网址的分隔符
    public $mustcontain=array();
    public $notcontain=array();
    //通过正则提取网址
    public $urlsre="";

    public function __construct(){
    
    
    }

    //添加提取网址的规则
    public function add_rules(){
        $argnum = func_get_args();
        if($argnum==1){
            $this->urlsre=func_get_arg(0);
            return true;
        }else if($argnum==2 && is_array(func_get_arg[0])){
            $this->mustcontain=func_get_arg[0];
            $this->notcontain=func_get_arg[1];
            return true;
        }else if($argnum==2 && is_string(func_get_arg[0])){
            $this->htmlstart=func_get_arg[0];
            $this->htmlend=func_get_arg[1];
            return true
        }
        return false; 
    }
    
    //填入所有URL
    //包含两种:正常URL和range
    //range格式如下: http://www.example.com/page/(range(start,stop,step)).html
    //传入的参数为数组
    public function add_urls($urls){
        foreach($urls as $url){
        
        
        }
    }













}
