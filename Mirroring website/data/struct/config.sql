/*
配置数据库
域名和分类配置
用来解决多域名访问中的问题
*/
create table domains(
id integer Primary key autoincrement,
domain varchar(50) not null unique,
title varchar(50) not null,
permalink varchar(20) not null,
template varchar(15) not null default 'index.php'
);
create table category(
id integer primary key autoincrement,
domainid integer not null,
name varchar(20) not null,
categorytemplate varchar(15) default 'category.php',
singletemplate varchar(15) default 'single.php'
)
create index domian on domains(domain);
create index domainid on category(domainid);
