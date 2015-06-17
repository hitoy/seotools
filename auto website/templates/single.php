<?php
$postname=getpostname();
$yahoo=new Yahoo($postname);
$content=$yahoo->get_the_content();
if(empty($content)){
$content=(new Bing($postname))->get_the_content();
}
if(empty($content)){
header("HTTP/1.1 404 Not Found");
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="utf-8">
<title><?php echo $postname;?></title>
<link rel="stylesheet" type="text/css" href="/templates/style.css"/>
</head>
<body>
<div class="head">
<h1><?php echo webname;?></h1>
</div>
<div class="banner"></div>
<div class="main">
<p>A brick is a block or a single unit of a kneaded clay-bearing soil, sand and lime, or concrete material, fire-hardened or air-dried, used in masonry construction. Lightweight bricks (also called "lightweight blocks") are made from expanded clay aggregate. Fired bricks are the most numerous type and are laid in courses and numerous patterns known as bonds, collectively known as brickwork, and may be laid in various kinds of mortar to hold the bricks together to make a durable structure. Bricks are produced in numerous classes, types, materials, and sizes which vary with region and time period, and are produced in bulk quantities. Two basic categories of bricks are fired and non-fired bricks. Fired bricks are one of the longest-lasting and strongest building materials, sometimes referred to as artificial stone, and have been used since circa 5000 BC. Air-dried bricks, also known as mudbricks, have a history older than fired bricks, and have an additional ingredient of a mechanical binder such as straw.</p>
<p>
BCM is a professional manufacturer and exporter with advanced brick making machine and other machinery. As one of the greatest brick making manufacturers in China, with both R&D and production capabilities, the company possesses superior technology and equipment. Strong support is provided by the R&D team and considerate after-sales service employees.</p>
<div class="content">
<h1><?php echo $postname?></h1>
<div class="article">
<script src="/templates/brick.js"></script>
<img src="/templates/oil.png" alt="oil press" class="titlebanner"/>
<?php
echo showimglist($postname,$content);
?>
</div>
<?php get_comment()?>
</div>
<?php get_sidebar()?>
<div class="clear"></div>
</div>
<?php get_footer()?>
