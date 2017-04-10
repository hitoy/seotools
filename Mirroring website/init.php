<?php
/* 初始化文件
 * 2017年3月6日
 */
require_once(dirname(__FILE__)."./config.php");
require_once(LIBPATH."db.class.php");
require_once(LIBPATH."filesystem.class.php");

//配置信息数据库
$configdb = new SQLite("config");
//文章数据库
$articledb = new SQLite("article");
/*
$articledb->exec("
    create table article(
id integer primary key autoincrement,
cate_name integer not null,
domainid integer not null,
title TEXT,
slug TEXT,
keyword TEXT,
tag TEXT,
excerpt TEXT,
content TEXT not null
);
create index cate_name on article(cate_name);
create index domainid on article(domainid);
create index title on article(title);
create index slug on article(slug);
create index tag on article(tag);
create index content on article(content)");

die();
*/
//$articledb->exec("insert into article(id,cate_name,domainid,title,slug,content) values(5,'case',2,'test title','test-title','1:0:10')");
//$articledb->exec("update article set content = '1:0:52' where domainid = '2'");
