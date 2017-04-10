create table cookie(id INTEGER PRIMARY KEY AUTOINCREMENT,data TEXT NOT NULL,expires INT NOT NULL,path TEXT NOT NULL,domain text NOT NULL);
create index expires on cookie (expires);
create index domain on cookie (domain);
create index path on cookie(path);
