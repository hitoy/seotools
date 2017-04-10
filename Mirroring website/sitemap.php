<?php
/*
 *
 * sitemap功能
 */
if(file_exists('./caches/sitemap-'.$page.".xml") && $page !=0){
    header("Content-Type:text/xml");
    readfile('./caches/sitemap-'.$page.'.xml');
    die;
}else if($page==0){
    $tmp = $articledb->query("select count(id) as size from article where domainid =$domainid");
    $size = $tmp->fetchArray(1)['size'];
    if($size > SitemapSize){
        header("Content-Type:text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
        $maps = ceil($size/SitemapSize)+1;
        for($i=1;$i<$maps;$i++){
            echo "<sitemap><loc>http://$domain/sitemap-$i.xml</loc></sitemap>\r\n";
        }   
        echo "</sitemapindex>";
        die;
    }
}

$offset = ($page-1)*SitemapSize;
$tmp = $articledb->query("select slug from article where domainid =$domainid limit $offset,".SitemapSize);
$list=$tmp->fetchArray(1);
if(empty($list)) error(404);
$i=0;
ob_start();
header("Content-Type:text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo "\r\n";
while($list){
    echo "<loc><url>http://".$domain.'/'.$list['slug'].".html</url></loc>\r\n";
    $list=$tmp->fetchArray(1);
    $i++;
}
echo '</urlset>';
$xml = ob_get_contents();
ob_end_flush();
if($i==SitemapSize){
    file_put_contents("./caches/sitemap-$page.xml",$xml);
}
