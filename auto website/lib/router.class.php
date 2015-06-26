<?php
class Router{
    public function __construct($name){
        $this->name=trim($name);
        if($this->name=="/" || $this->name == "/index.php"){
            $this->type="home";
        }else if(substr($this->name,0,10)=='/category/'){
            $this->type="category";
        }else if(substr($this->name,0,12)=='/sitemap.xml'){
            $this->type="sitemap";
        }else{
            $this->type="single";
        }
    }


    public function route(){
        global $list;
        if(file_exists("templates/functions.php")){
            require("templates/functions.php");
        }
        if($this->type=="home"){
            require("templates/index.php");
        }else if($this->type=="category"){
            require("templates/category.php");
        }else if($this->type=="sitemap"){
            require("templates/sitemap.php");
        }else if($this->type=="single"){
            require("templates/single.php");
        }
    }
}
