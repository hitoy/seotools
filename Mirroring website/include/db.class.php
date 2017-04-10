<?php
/*
 * SQLITE数据库操作
 * 杨海涛 2017年3月8日
 */
class SQLITE extends SQLite3{
    //dbname不带后缀
    public function __construct($dbname){
        $this->open(DBPATH.$dbname.".db");
    }

    public function __destruct(){
        $this->close();
    }
}
