<?php
/*
Plugin Name: delete short posts
Plugin URI: https://www.hitoy.org/
Description: delete short content posts
Version: 0.0.1
Author: Hito
Author URI: https://www.hitoy.org/
License: GPL2
*/
if(!is_admin()) return false;

function display_delete_menu(){
		add_options_page('delete short posts', 'delete short posts', 'manage_options','delete-short-posts', 'show_delete_manage');
}
function show_delete_manage(){
		do_delete();
?>
		<div class="wrap">
		<p>删除字数小于指定数量的文章</p>
		<form action="" method="post" onsubmit="return confirm('你确定要删除字数少于'+this.words.value+'的文章?\n删除后不可恢复!')">
			<input type="text" name="words">
			<input type="submit" value="删除">
		</form>
<?php
}

function do_delete(){
	if(!isset($_POST) || !isset($_POST['words'])) return false;
	$words = (int) $_POST['words'];
	global $wpdb;
	$wpdb->query("delete from wp_posts where length(post_content) < $words");
}

add_action('admin_menu', 'display_delete_menu');
