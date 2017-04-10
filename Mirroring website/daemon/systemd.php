<?php
/*
 * 一直在后台运行的守护进程
 * 要脱离浏览器而存在
 */
if(file_exists(dirname(__FILE__)."/systemd.lock")) exit("Daemon is Runing");
function clean(){
   unlink(dirname(__FILE__)."/systemd.lock");
   unlink(dirname(__FILE__)."/kill");
}
function kill(){
    touch(dirname(__FILE__)."/kill");
}

ob_start();
register_shutdown_function("clean");
touch(dirname(__FILE__)."/systemd.lock");
set_time_limit(0);
ignore_user_abort(true);
header("HTTP/1.1 200 OK");
header("Content-Length:0");
header("Connection: Close");
ob_end_flush();
while(true){
    /*
     * 这里是后台的动作
     *
     */
    if(file_exists(dirname(__FILE__)."/kill")) break;
    usleep(500000);
}
