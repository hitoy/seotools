/*
 文章数据库
 用来存放文章数据，不包括内容
 content blockname:startpos:endpos
*/
create table article(
id integer primary key autoincrement,
cate_name integer not null,
domainid integer not null,
title varchar(255) not null,
slug varchar(255) not null,
keyword varchar(100),
tag varchar(100),
excerpt varchar(255) not null,
thumbnail varchar(100) default 'default-thumbnail.jpg',
content varchar(100) not null
);
create index cate_name on article(cate_name);
create index domainid on article(domainid);
create index title on article(title);
create index slug on article(slug);
create index tag on article(tag);
create index content on article(content);
