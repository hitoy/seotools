<?php
/*
 * 
 */
require_once('dm-load.php');

//每天的文章数量
$posts_per_day = 20;

//总共的文章数量
$total_posts = $dmdb->get_var('select count(ID) from ##_posts');

//需要的天数
$total_days = ceil($total_posts/$posts_per_day);

//最开始的时间
$starttime = time() - $total_days * 3600*24;

//当前ID
$id = intval($_GET['id']);

//前面的post条数
$offset = $dmdb->get_var('select count(ID) from ##_posts where ID < '.$id);


for($i = 0; $i < 1000; $i++){
    //应该增加的天数
    $addtime = floor($offset / $posts_per_day) * 3600 * 24;
    $post_date = date('Y-m-d H:i:s',$starttime + $addtime + rand(0,3600*24));

    $result = $dmdb->query("update ##_posts set post_date = \"$post_date\", post_modified=\"$post_date\" where ID = $id");
    $offset += 1;
    $id = $id + 1;
}

$nxt = $id + 1;
if(!empty($result) && $dmdb->get_var('select ID from ##_posts where ID > '.$id)){
    echo "<meta http-equiv=\"refresh\" content=\"0;url=".home_url(1)."modifie.php?id=$nxt\">\r\n";
    echo "总文章条数:$total_posts \t 每天的文章条数: $posts_per_day \t 已经更改文章: $offset";
}else{
    echo "更新完成!";
}
