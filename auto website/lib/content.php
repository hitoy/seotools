<?php
require_once(ABPATH."/lib/content.class.php");

function curl($url,$data=false){
	$ssl = substr($url, 0, 8) == "https://" ? true : false;
	if(!function_exists("curl_init") && $data == "") return file_get_contents($url);
	if(!function_exists("curl_init") && $data !=""){throw new Exception("Can Not Send a request!");return;}
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HEADER,0); 
	curl_setopt($ch,CURLOPT_NOBODY,0); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36");
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
	if($data){
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	}
	if($ssl){
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
	}
	$body = curl_exec($ch);
	curl_close($ch);
	return $body;
}

function getimgabsrc($url,$match){
	if(substr($match[2],0,2)==='//'){
		 preg_match("/^(https?:\/\/[^\/]*)\//",$url,$m);
		 return $match[1].'"'.$m[1].substr($match[2],2).'"'.$match[3];;
	}if(substr($match[2],0,1)==='/'){
		 preg_match("/^(https?:\/\/[^\/]*)\//",$url,$m);
		 return $match[1].'"'.$m[1].$match[2].'"'.$match[3];
	}else if(substr($match[2],0,4)==='http'){
		return $match[0];
	}else if(substr($match[2],0,2) ==='..' || substr($match[2],0,1) ==='.'){
		$src = substr($url,0,strrpos($url,"/")+1);
		return str_replace("//","/",$match[1].'"'.$src.'/'.$match[2].'"'.$match[3]);
	}
	return $match[0];
}

function getabstract($content,$len=500){
	preg_replace("/<h1>[\s\S]+<\/h1>/","",$content);
	$content = strip_tags($content);
	return mb_substr($content,0,$len);
}

class CONTENT{
	//关键词文件
	public $keyfile;
	//一页显示的个数
	public $pagesize;
	//总记录数
	public $totalrecord;
	//显示摘要的个数
	public $abstract;

	public function __construct($keyfile,$pagesize=10,$abstract=255){
		$this->keyfile=$keyfile;
		$this->pagesize=$pagesize;
		$this->abstract=$abstract;
	}

	public function get_the_list($offset=0){
		$this->offset=$offset;
		$list = Array();
		$fd = fopen($this->keyfile,"r");
		$current = 0;
		while(!feof($fd)){
			if($current >= $offset && $current < ($offset + $this->pagesize)){
				$line = trim(fgets($fd));
				if(!$line){$offset++;continue;}
				if(preg_match("/^https?:\/\//",$line)){
					$all = $this->get_the_content_by_url($line);
					if(!$all){$offset++;continue;}
					$content = $all['content'];
					$title = $all['title'];
				}else{
					$title = $line;
					$content = $this->get_the_content_by_key($line);
				}
				if(empty($content) || strlen($content) < 200){continue;}
				$id = $offset+$current+1;
				$mtime = filemtime($this->keyfile)-$id*rand(3600,36000);
				array_push($list,array("title"=>$title,"titlehash"=>sha1($line),"id"=>$id,"mdate"=>$mtime,"content"=>$content));
			}else{
				fgets($fd);
			}
			$current++;	
		}
		$this->totalrecord=$current;
		fclose($fd);	
		return $list;
	}

	public function get_the_hot($offset=0,$size=10){
		$list = Array();
		$fd = fopen($this->keyfile,"r");
		$current = 0;
		while(!feof($fd)){
			if($current >= $offset && $current < ($offset + $size)){
				$title = trim(fgets($fd));
				if(!$title){$offset++;continue;}
				if(preg_match("/^https?:\/\//",$title)){
					$all = $this->get_the_content_by_url($title);
					if(empty($all)){$offset++;continue;}
					$content = $all['content'];
					$title = $all['title'];
				}else{
					$content = $this->get_the_content_by_key($title);
				}
				if(empty($content) || strlen($content) < 200){continue;}
				$id = $offset+$current+1;
				$mtime = filemtime($this->keyfile)-$id*rand(3600,36000);
				array_push($list,array("title"=>$title,"titlehash"=>sha1($title),"id"=>$id,"mdate"=>$mtime,"content"=>$content));
			}else if($current > $offset + $size){
				break;
			}else{
				fgets($fd);
			}
			$current++;	
		}
		fclose($fd);	
		return $list;
	}

	public function pageinfo(){
		$totalpage = ceil($this->totalrecord/$this->pagesize);
		$currentpage = floor($this->offset/$this->pagesize)+1;
		if($currentpage==1){
			$prev = "";
		}else{
			$prev='<a href="/page/'.($currentpage-1).'">Prev Page</a>';
		}

		if($currentpage==$totalpage){
			$next = "";
		}else{
			$next='<a href="/page/'.($currentpage+1).'">Next Page</a>';
		}
		return '<nav class="navigation">'.$prev.$next.'</nav>';
	}

	public function get_the_content_by_key($key){
		$sha1index = sha1($key);
		$html="";
		if(file_exists(ABPATH."/cache/".$sha1index)){
			return file_get_contents(ABPATH."/cache/".$sha1index);
		}else{
			//采集搜索引擎结果
			$a = new Yahoo($key);
			$html = $a->get_the_content();
			if(empty($html) || strlen($html)<600){
				$b = new Bing($key);
				$html = $b->get_the_content();
				if(empty($html) || strlen($html)<600){
					$c = new Yandex($key);
					$html = $b->get_the_content();
				}
			}
		}
		if(empty($html) || strlen($html) <= 100) return false;
		//写入缓存
		file_put_contents(ABPATH."/cache/".$sha1index,"<h1>".$key."</h1>".$html,LOCK_EX);
		//返回内容
		return $html;
	}

	public function get_the_content_by_url($url){
		$sha1index = sha1($url);
		if(file_exists(ABPATH."/cache/".$sha1index)){
			$content = file_get_contents(ABPATH."/cache/".$sha1index);
			$title = $this->get_html_title($content);
			return array("title"=>$title,"content"=>$content);
		}else{
			//采集相关页面的结果
			$html = curl($url);
			$content = curl("http://lab.hitoy.org/api/articleExtract/gettext.php",array("content"=>$html,"key"=>"hizg123"));	
			define("imgurl",$url);
			$content = preg_replace_callback("/(<img.*?src=)[\"\'](.*?)[\"\']([^>]*>)/i",function($match){
					return getimgabsrc(imgurl,$match); 
			},$content);
			$title = $this->get_html_title($html);
			$title = trim($title);
			if(empty($content) || empty($title) || strlen($content) <= 100) return false;
			//写入缓存
			file_put_contents(ABPATH."/cache/".$sha1index,"<h1>".$title."</h1>\r\n".$content,LOCK_EX);
			//file_put_contents(ABPATH."/cache/".sha1($title),"<h1>".$title."</h1>\r\n".$content,LOCK_EX);
			return array("title"=>$title,"content"=>$content);
		}
	}

	public function get_html_title($html){
		preg_match("/<title>([\s\S]*)<\/title>/i",$html,$title);
		preg_match("/<h1>([\s\S]*)<\/h1>/i",$html,$h1);
		if($title){
			preg_match("/^([^-|_]*)\s?/i",trim($title[1]),$t);
			return $t[1];
		}else if($h1){
			preg_match("/^([^-|_]*)/i",trim($h1[1]),$t);
			return $t[1];
		}
		return NULL;
	}
}
