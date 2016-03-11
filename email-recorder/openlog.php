<?php
$mimetype = array("jpg"=>"image/jpeg","png"=>"image/png","gif"=>"image/gif");
$request  = $_SERVER["REQUEST_URI"];
if(strpos($request,"?")){
	$filename = substr($request,0,strpos($request,"?"));
}else{
	$filename = $request;
}
$mail = isset($_GET["mail"])?$_GET["mail"]:"";

$subfix = substr($filename,strpos($filename,".")+1);
if(array_key_exists($subfix,$mimetype)){
	$mime = $mimetype[$subfix];
}else{
	$mime = "text/html";
}

if(file_exists($_SERVER["DOCUMENT_ROOT"].$filename)){
	$content = file_get_contents($_SERVER["DOCUMENT_ROOT"].$filename);
	$len = strlen($content);
	header("Content-Length:$len");
	header("Content-Type:$mime");
	echo $content;
	if(!empty($mail)){
		file_put_contents("emailopen.txt",$_SERVER["REMOTE_ADDR"]." - ".$mail." - ".date("Y-y-d H:i:s")."\r\n",LOCK_EX|FILE_APPEND);
	}
}else{
	header("HTTP/1.1 404 Not Found");
}
