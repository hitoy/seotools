<?php
/* ZIP文件解压系统
 * 杨海涛 2015年12月23日
 */
header("Content-Type:text/html;charset=utf-8");
$file = !empty($_GET['file'])?trim($_GET['file']):"";
if(!file_exists($file)) exit("文件不存在!");
if(!function_exists('zip_open')) exit("服务器不支持ZIP解压!");

$own = fileowner($file);
$grp = filegroup($file);


$zip = zip_open($file);

if(!$zip) exit("未知错误!");

while($zip_entry = zip_read($zip)){
	$fname = zip_entry_name($zip_entry);
	echo $fname,"<br/>";
	mkdirs($fname);
	if(zip_entry_open($zip, $zip_entry)){
		$fsize = zip_entry_filesize($zip_entry);
		$fcontent = zip_entry_read($zip_entry,$fsize);
		write($fname,$fcontent,$own,$grp);
		zip_entry_close($zip_entry);
	}
}
zip_close($zip);

function write($fname,$content,$own=false,$grp=false,$mod=0666){
	if(strrchr($fname,"/") == "/") return;
	$f = fopen($fname,"wb+");
	flock($f,LOCK_EX);
	fwrite($f,$content);
	flock($f,LOCK_UN);
	fclose($f);
	if($own) @chown($fname,$own);
	if($grp) @chgrp($fname,$grp);
	if($mod) chmod($fname,$mod);
}

function mkdirs($dirs,$own=false,$grp=false,$mod=0777){
		$dirs= ltrim($dirs,"/\\");
		$len = strlen($dirs);	
		$path = "";
		for($i = 0 ; $i < $len; $i ++){
			$char = substr($dirs,$i,1);
			$path .= $char;
			if($char=="/"){
				if(!file_exists($path)) mkdir($path);
				if($own) @chown($path,$own);
				if($grp) @chgrp($path,$grp);
				if($mod) chmod($path,$mod);
			}
		}
}
