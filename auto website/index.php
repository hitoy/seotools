<?php
require_once("./init.php");
/* $router 路由类
 * $list   列表类
 *
 */

$list->add_list("Brick Making Machine",ABPATH."/data/1.txt");
$router->route();
