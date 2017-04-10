/*
对每个job单独建立一个数据库命名方式为jobid.db
结构如下:
*/
create table pageurl(
id integer primary key autoincrement,
url TEXT UNIQUE,
successed boolean default false
)
