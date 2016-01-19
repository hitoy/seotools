<?php
ob_start();
header("Content-Type:text/xml;charset=utf-8");
header("Cache-Control:max-age=10800");
$len = 10000;
$dir = opendir(ABPATH."cache/");
?>
<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
<loc>http://<?php echo host;?>/</loc>
</url>
<?php
$i==0;
while(($file=readdir($dir)) !== false && $i<$len){
	if($file == "." || $file == "..") continue;
	echo "<url><loc>http://".$_SERVER["HTTP_HOST"]."/".$file."</loc></url>\r\n";
	$i++;
}
closedir($dir);
?>
</urlset>
<?php
$sitemap = ob_get_contents();
ob_end_clean();
$len = strlen($sitemap);
if(!empty($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == md5($sitemap)){
	header("HTTP/1.1 304 Not Modified");
	header("Vary:etag");
	exit();
}
header("Content-Length:$len");
header("Etag:".md5($sitemap));
echo $sitemap;
?>
