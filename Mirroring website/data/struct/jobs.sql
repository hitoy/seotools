/*
    采集任务库
    包含从初级地址提取目标地址的数据
    cate所属栏目
    htmlstart:提取链接开始的HTML
    urlmustcontain:url中必须含有的字段
    urlsre: 通过正则提取URL
    domainid:所属的域名
    
    用户填写的URL会生成到第二个数据库job_id.db中
*/
create table jobs(
id integer primary key autoincrement,
domainid integer,
taskname TEXT,
cate TEXT,

/*爬行的useragent*/
useragent varchar(255),

/*提取URL的关键点*/
htmlstart TEXT,
htmlend TEXT,
urlmustcontain TEXT,
urlnotcontain TEXT,
/*提取title的关键点*/
/*start和end代表提取内容的标签*/
/*callback代表获取内容的回调函数代码，参数为获取的title*/
titlestart TEXT,
titleend TEXT,
titlecallback TEXT,

keywordstart TEXT,
keywordend TEXT,
keywordcallback TEXT,

tagstart TEXT,
tagend TEXT,
tagcallback TEXT,
/*同上 loopmath为正则的循环匹配*/
contentstart TEXT,
contentend TEXT,
loopmatch Boolean default false,
contentcallback TEXT,
);
create index cate on jobs(cate);
