<?php
/*
 * 根据TXT获取JOB并生成URL保存到数据库中
 *
 */

$jobs = file_get_contents("./jobs.txt");
preg_match_all("/={3,}[\r\n]*([\s\S]*)={3,}/i",$jobs,$match);
print_r($match);
