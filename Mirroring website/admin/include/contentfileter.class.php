<?php
/*
 * 内容处理规则
 * 杨海涛 2017年3月9日
 */
class ContentFilter{
    protected $content;

    public function __construct($content){
        $this->content=$content;
    }

    /*字符串替换*/
    public function strreplace($find,$replace,$ignore=true){
        if()
            if($ignore==true){
                $this->content=str_ireplace($find,$replace,$this->content);
            }else{
                $this->content=str_replace($find,$replace,$this->content);
            }
        }else if(is_array($find) && is_array($replace)){
        
        }
    }













}
