<?php
require_once(ABPATH."/lib/content.php");
$list = new CONTENT(keyfile);
$offset = ($page-1)*10;
$the_content = $list->get_the_list($offset);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="applicable-device" content="pc,mobile">
<meta name="referrer" content="always">
<title><?php echo webname." - ".host?></title>
<link rel="stylesheet" type="text/css" media="all" href="//<?php echo host;?>/templates/media/style.css?v=1"/>
</head>
<body>
<header class="siteheader">
    <div class="top">
	<a href="//<?php echo host;?>/" rel="home"><?php echo webname;?></a>
        <nav class="primary">
			<ul>
				<li><a href="//<?php echo host;?>">Home</a></li>
				<li><a href="//<?php echo host;?>">Boilers</a></li>
				<li><a href="//<?php echo host;?>">Case</a></li>
				<li><a href="//<?php echo host;?>">About Us</a></li>
				<li><a href="http://data.zgboilers.com/tailored/">Tailored Boilers</a></li>
			</ul>
		</nav>
    </div>
</header>
<div class="content">
<main role="main">
<div class="banner">
	<img src="//<?php echo host;?>/templates/media/banner<?php echo rand(1,18);?>.jpg" alt="Industrial Boiler Expert">
</div>
<?php
foreach($the_content as $single){
?>
	<article id="post-<?php echo $single['id']?>" class="post">
	<header><h2><a href="//<?php echo host;?>/<?php echo $single['titlehash']?>"><?php echo $single['title'];?></a></h2></header>
		<div class="entry-content">
			<?php echo getabstract($single['content'], 600);?>
            </div>
            <footer class="entry-footer">
			<span class="pubdate"><time datetime="<?php echo date("Y-m-d H:i:s",$single['mdate']);?>" pubdate="pubdate"><?php echo date("Y-m-d",$single['mdate']);?></time></span>
				<span class="comments"><a href="//<?php echo host;?>/<?php echo $single['titlehash']?>#respond">GET DETAIL</a></span>
                <span class="category"><a href="//<?php echo host;?>/<?php echo $single['titlehash']?>">MORE</a></span>
            </footer>
    </article>
<?php
}
echo $list->pageinfo();
?>
</main>
<aside>
<section class="widget">
<h2>History</h2>
<p>
Zhengzhou Boiler Co,. Ltd have been manufacturing industrial boilers including Steam Boilers, Hot Water Boilers and Waste Heat Boilers for over 70 years. We are a joint-stock enterprise with A1, A2, C3 pressure vessel design and manufacture license permits.
<img src="//<?php echo host;?>/templates/media/70years.png" alt="70 years history boiler factory" width="100%">
</p>
</section>
<section class="widget">
<h2>Latest Article</h2>
<ul>
<?php
$size = rand(5,10);
$list_content = $list->get_the_hot(0,$size);
foreach($list_content as $s){
	echo '<li><a href="//'.host.'/'.$s['titlehash'].'">'.$s['title'].'</a></li>';
}
?>
</ul>
</section>
<section class="widget">
<h2>Hot Article</h2>
<ul>
<?php
$size = 5;
$offset = floor($list->totalrecord/rand(2,10));
$randlist = $list->get_the_hot($offset,$size);
foreach($randlist as $list){
echo '<li><a href="//'.host.'/'.$list['titlehash'].'">'.$list['title'].'</a></li>';
}
?>
</ul>
</section>
</aside>
</div>
<div style="clear:both"></div>
<footer class="sitefooter">
<div class="fo"><span class="copy">&copy; 2016 <?php echo host?></span></div>
</footer>
<script language="javascript" src="http://pat.zoosnet.net/JS/LsJS.aspx?siteid=PAT67433781&float=1&lng=en"></script>
</body>
</html>
