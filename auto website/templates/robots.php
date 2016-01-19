<?php
ob_start();
header("Content-type:text/plain");
header("Cache-Control:max-age=10800");
$sitemap = "http://".$_SERVER["HTTP_HOST"]."/sitemap.xml"
?>
User-agent: *
sitemap: <?php echo $sitemap."\r\n";?>

Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /data/
Disallow: /cache/
Allow: /
<?php 
$robots = ob_get_contents();	
ob_end_clean();
$len = strlen($robots);
if(!empty($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"] == md5($robots)){
	header("HTTP/1.1 304 Not Modified");
	header("Vary:etag");
	exit();
}
header("Content-length: $len");
header("Etag: ".md5($robots));
echo $robots;
?>
