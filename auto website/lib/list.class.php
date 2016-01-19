<?php
class ContentList{
    private $list;

    public function __construct(){
        $this->totalpage=0;
        $this->totalrecord=0;
        $this->currentpage=0;
        $this->list=array();
    }


    public function add_list($catname,$catkeyfile){
        if(!array_key_exists($catkeyfile,$this->list) && file_exists($catkeyfile) && is_readable($catkeyfile)){
            $this->list[$catname]=$catkeyfile;
        }
    }


    public function showlist($catname,$offset,$pagesize){
        if(!array_key_exists($catname,$this->list)){
            return array();
        }

        $page=array();
        $fd=fopen($this->list[$catname],"r");
        $pointer=0;
        while(!feof($fd)){
            if($pointer >= $offset && $pointer<($offset+$pagesize)){
                $title=trim(fgets($fd));
                $url=urlencode(preg_replace("/[\s]+/i","-",$title));
                if(strlen($title)>0){
                    array_push($page,array('title'=>$title,'link'=>$url));
                }
            }else{
                fgets($fd);
            }
            $pointer++;
        }
        $this->totalpage=ceil($pointer/$pagesize);
        $this->currentpage=$offset/$pagesize+1;
        $this->totalrecord=$pointer;
        return $page;
    } 

    public function showpage($tag="li"){
        $r="";
        $i=1;
        while($i <= $this->totalpage){
            if($i == $this->currentpage){
                $r .="<$tag>".$i."</$tag>";
            }else{
                $r .="<$tag><a href=\"$i\">".$i."</a></li>";
            }
            $i++;
        }
        if($tag=="li"){
            $r="<ul>$r</ul>";
        }
        return $r;
    }


    public function showall(){
        $urls=array();
        foreach($this->list as $s){
            $fd=fopen($s,"r");
            while(!feof($fd)){
                $title=trim(fgets($fd));
                if(strlen($title) > 0){
                    $url=urlencode(preg_replace("/[\s]+/i","-",$title));
                    array_push($urls,$url);
                }
            }
            fclose($fd);
        }
        return $urls;
    }


    public function __destruct(){
        if(!empty($this->keyfd)){
            fclose($this->keyfd);   
        }
    }
}
