<?php
error_reporting(E_ALL);

ob_start();
header("Content-Type:text/xml;Charset=utf-8");
$sitemap = $list->showall();
$siteMapStart= '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$siteMapEnd= "</urlset>";
//计数
$k=0;
$sitemapIndex="";
//sitemap 5000一组 总数量
$siteMapCount=ceil(count($sitemap)/5000);
for ($i=0; $i <$siteMapCount ; $i++) { 
	$line="";
	for ($j=0; $j < 5000 && $k<count($sitemap); $j++) { 
		$line.="<url><loc>".host.$sitemap[$k].".html</loc></url>";
		$k++;
	}
	$sitemapIndex.="<url><loc>".host."sitemap{$i}.xml</loc></url>";
	
	$handle=fopen($_SERVER['DOCUMENT_ROOT']."/cache/sitemap{$i}.xml", "w");
	$line=$siteMapStart.$line.$siteMapEnd;
	fwrite($handle,$line );
	fclose($handle);
}

$handle=fopen($_SERVER['DOCUMENT_ROOT']."/cache/sitemap.xml", "w");
$sitemapIndex=$siteMapStart.$sitemapIndex.$siteMapEnd;
fwrite($handle,$sitemapIndex );
fclose($handle);
ob_end_flush();
echo $sitemapIndex;
?>
