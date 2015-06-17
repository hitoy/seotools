<?php
require_once("./init.php");
/* $router 路由类
 * $list   列表类
 *
 */

$list->add_list("Brick Making Machine",ABPATH."/data/1.txt");
$list->add_list("Block Making Machine",ABPATH."/data/2.txt");
$list->add_list("Brick Machine",ABPATH."/data/3.txt");
$list->add_list("Block Machine",ABPATH."/data/4.txt");
$list->add_list("Products",ABPATH."/data/5.txt");
$list->add_list("Solution",ABPATH."/data/6.txt");
$list->add_list("Case",ABPATH."/data/7.txt");
$router->route();
