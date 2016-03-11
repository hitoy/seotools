<?php
$unsubscribe = isset($_GET['mail'])?trim($_GET['mail']):"";
if(!$unsubscribe){
	header("HTTP/1.1 404 Not Found");
	echo "Violation Access!";
	exit();
}
if(!preg_match("/^[a-zA-Z1-9-_\.]+@[a-zA-Z1-9-_\.]+\.[a-zA-Z]{2,6}/i",$unsubscribe)){
	header("HTTP/1.1 404 Not Found");
	echo "Violation Access!";
	exit();
}
$unsubfile = fopen("unsubscribe.txt","a+");
do{
	usleep(100);
}while(!flock($unsubfile,LOCK_EX));
fwrite($unsubfile,$_SERVER["REMOTE_ADDR"]." - ".$unsubscribe." - ".date("Y-m-d H:i:s")."\r\n");
fclose($unsubfile);
?>
<!DOCTYPE HTML>
<html lang="en-us">
<head>
<meta charset="utf-8">
<title>Unsuscribe - Sorry to Disturb You</title>
<meta name="viewport" content="width=device-width;">
<style>
* {margin:0;padding:0}
.notice {width:300px;margin:50px auto;background:#eee;padding:50px;text-align:center;border-radius:10px;color:#000;font-size:15px;font-family:"Arial"}
</style>
</head>
<body>
<div class="notice">
Unsubscribe success!<br/>
<script>
setTimeout(function(){
	window.close();
},8000)
	</script>
</div>
</body>
</html>
