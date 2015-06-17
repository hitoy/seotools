<?php
$catname=getcatename()['catname'];
$page=getcatename()['page'];
$catecontent=$list->showlist($catname,($page-1)*80,80);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="utf-8">
<title><?php echo $catname," - ",webname;?></title>
<link rel="stylesheet" type="text/css" href="/templates/style.css"/>
</head>
<body>
<div class="head">
<h1><?php echo webname;?></h1>
</div>
<div class="banner"></div>
<div class="main">
<p>A brick is a block or a single unit of a kneaded clay-bearing soil, sand and lime, or concrete material, fire-hardened or air-dried, used in masonry construction. Lightweight bricks (also called "lightweight blocks") are made from expanded clay aggregate. Fired bricks are the most numerous type and are laid in courses and numerous patterns known as bonds, collectively known as brickwork, and may be laid in various kinds of mortar to hold the bricks together to make a durable structure. Bricks are produced in numerous classes, types, materials, and sizes which vary with region and time period, and are produced in bulk quantities. Two basic categories of bricks are fired and non-fired bricks. Fired bricks are one of the longest-lasting and strongest building materials, sometimes referred to as artificial stone, and have been used since circa 5000 BC. Air-dried bricks, also known as mudbricks, have a history older than fired bricks, and have an additional ingredient of a mechanical binder such as straw.</p>
<div class="content">
<h1 class="categoryh1"><?php echo $catname?></h1>
<ul>
<?php
foreach($catecontent as $k){
    echo "<li><a href=\"/".$k['link'].".html\">".$k['title']."</a></li>\r\n";
}
?>
</ul>
</div>
<?php get_sidebar()?>
<div class="page">
<?php echo $list->showpage();?>
</div>
</div>
<?php get_footer();?>
