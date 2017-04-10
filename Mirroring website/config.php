<?php
/*
 * 配置文件
 * 所有的PATH路径，最后必须带"/"
 */

define("ABPATH",realpath(dirname(__FILE__))."/");
define("DBPATH",ABPATH."data/");
define("LIBPATH",ABPATH."include/");

//文件系统块大小，单位字节
define("FileSystem_blockSize",10485760);
//文件系统路径
define("FileSystem_StoragePath", ABPATH."filesystem/");

//网站列表中，一页显示的列表数量
define("ListPageSize",1);
//单个sitemap包含网址的个数
define("SitemapSize",2000);
