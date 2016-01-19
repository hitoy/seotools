<?php
class Router{
    public function __construct($name){
        $this->name=trim($name);
        if($this->name=="/" || $this->name == "/index.php"){
            $this->type="home";
		}else if(substr($this->name,0,6) == "/page/"){
			$this->type="page";
        }else if(substr($this->name,0,10)=='/category/'){
            $this->type="category";
		}else if(substr($this->name,0,11) == '/robots.txt'){
			$this->type="robots";
        }else if(substr($this->name,0,12) == '/sitemap.xml'){
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
			$page = 1;
            require("templates/index.php");
		}else if($this->type=="page"){
			preg_match("/^\/page\/(\d*)/i",$this->name,$pagear);
			if($pagear){
				$page = $pagear[1];
			}else{
				$page = 1;
			}
			require("templates/index.php");
        }else if($this->type=="category"){
            require("templates/category.php");
        }else if($this->type=="sitemap"){
            require("templates/sitemap.php");
		}else if($this->type=="robots"){
            require("templates/robots.php");
        }else if($this->type=="single"){
			preg_match("/^\/(.*)/",$this->name,$tarr);
			if($tarr){
				$title = $tarr[1];
			}else{
				$title = "";
			}
            require("templates/single.php");
        }
    }
}
