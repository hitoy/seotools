<?php 
$cache = file_get_contents(ABPATH."cache/".$title);
preg_match("/<h1>(.*)<\/h1>/i",$cache,$tmp);
$the_title = $tmp[1];
preg_match("/<\/h1>([\s\S]+)$/i",$cache,$tmp);
$the_content = $tmp[1];
$the_date =filemtime(ABPATH."cache/".$title);
if($title=="" || $cache==""){
	header("HTTP/1.1 404 Not Found");
}
require_once(ABPATH."/lib/content.php");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="applicable-device" content="pc,mobile">
<meta name="referrer" content="always">
<title><?php echo $the_title." - ".host?></title>
<link rel="stylesheet" type="text/css" media="all" href="//<?php echo host;?>/templates/media/style.css?v=1"/>
</head>
<body>
<header class="siteheader">
	<div class="top">
	<a href="//<?php echo host;?>/" rel="home"><?php echo webname;?></a>
		<nav class="primary">
			<ul>
				<li><a href="//<?php echo host;?>/">Home</a></li>
				<li><a href="//<?php echo host;?>/">Boilers</a></li>
				<li><a href="//<?php echo host;?>/">Case</a></li>
				<li><a href="//<?php echo host;?>/">About Us</a></li>
				<li><a href="http://data.zgboilers.com/tailored/">Tailored Boilers</a></li>
			</ul>
		</nav>
	</div>
</header>
<div class="content">
<main role="main">
<div class="banner">
	<img src="//<?php echo host;?>/templates/media/banner<?php echo rand(1,18)?>.jpg" alt="Industrial Boiler Expert">
</div>
	<div class="bread">
		<a href="//<?php echo host;?>/">Home</a> &gt; <?php echo $the_title;?>
	</div>
   <article class="single">
   <header><h1><?php echo $the_title;?></h1></header>
   <footer>
	<span class="pubdate"><time datetime="<?php echo date("Y-m-d H:i:s",$the_date);?>" pubdate="pubdate"><?php echo date("Y-m-d",$the_date);?></time></span>
   </footer>
		<script src="/templates/media/form1.js"></script>
		<h2 style="font-size:18px;line-height:30px;color:#086ed5;margin:15px 0;background: #eee;text-indent:10px;border-radius:3px;">About Us</h2>
		<p>Zhengzhou Boiler Co., Ltd has been designing, engineering and servicing a complete line of industrial boiler(oil & gas fired boiler, chain grate boiler, circulating fluidized bed boiler, waste heat boiler) and autoclave since 1945, for thousands of satisfied customers. We have a fully equipped R & D team which carries out latest market research to understand the specific requirements of the clients and help us to procure products according to the market requirements which help us to grow in our business. They also assist us in providing better and cost effective after sale service of the products. Our customer service staff consists of professionals trained to answer any question you may have regarding your boiler, its proper operation, and maintenance. Our staff is available 24 hours a day, 7 days a week, around the world. Questions can be asked either on-line or by telephone, at your convenience, before, during or after the sale. <br/>
	Factory Address:NO.88 Science Road,High and New Technology Development District,Zhengzhou,China</p>
		<h2 style="font-size:18px;line-height:30px;color:#086ed5;margin:15px 0;background: #eee;text-indent:10px;border-radius:3px;">Our Main Product</h2>
		<img src="/templates/media/main-products.jpg" onclick="openZoosUrl('chatwin');" alt="our main boilers">
		<div class="entry-content">
		<?php echo showimglist($the_content)?>		
		</div>
	</article>
	<div id="comments">
			<div id="respond" class="comment-respond">
			<h3 id="reply-title" class="comment-reply-title">GET Support & Price<small></h3>
				<form action="http://data.zgboilers.com/post.php" method="post" id="respond-form" class="comment-form">
					<p>Please Feel free to give your inquiry in the form below.We will reply you in 24 hours</p>
					<p><label>Your Name:<span>*</span></label><input type="text" name="name" required="required"></p>
					<p><label>Your Email:<span>*</span></label><input type="email" name="email" required="required"></p>
					<p><label>Products:</label><input type="text" name="products" placeholder="what kind of boilers do you interested?"></p>
					<p><label>Message:</label><textarea name="message" required="required" placeholder="Please enter your demand such as fuel,capacity,steam pressure,application,etc"></textarea></p>
					<input type="hidden" name="url">
					<p class="form-submit"><input name="submit" type="submit" id="submit" class="submit" value="Submit" />
</p>				</form>
					</div>
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
$list=new CONTENT(keyfile);
$list_content = $list->get_the_hot(0,10);
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
$randlist = $list->get_the_hot(rand(100,200),5);
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
