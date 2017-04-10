<?php
/*
 * 文件管理模块
 * 考虑到存储到硬盘上的小文件较多，因此对文件进行打包和压缩处理
 * 以增加系统的效率，减少资源的浪费
 * 杨海涛 2017年3月7日
 */
class FileSystem{
    //文件后缀名
    public $suffix="gz";
    //块个数
    public $blockcount=0;
    //总占用大小
    public $systemsize=0;
    //最后一个块名称，在存储的时候需要用到
    //初始化为1
    public $lastblock=1;
    //压缩方式
    public $compressmethod;
    //压缩率
    //IO型网站，推荐压缩率设置为最大
    public $compresslevel;

    public function __construct($compressmethod="gzip",$compresslevel=9){
        $this->compressmethod=$compressmethod;
        $this->compresslevel=$compresslevel;
        $dir = opendir(FileSystem_StoragePath);
        $this->blockcount=0;
        $blocks=array();
        while(($file=readdir($dir))!==false){
            if($file=="."||$file =="..") continue;
            if(preg_match("/^\d\.".$this->suffix."/i",$file)){
                array_push($blocks,$file);
                $this->blockcount++;
                clearstatcache();
                $this->systemsize+=filesize(FileSystem_StoragePath.$file);
            }
        } 
        closedir($dir);
        array_multisort($blocks);
        //如果FileSystem_StoragePath没有存放文件，代表系统还生成
        if(!empty($blocks)){
            $this->lastblock=substr($blocks[$this->blockcount-1],0,strpos($blocks[$this->blockcount-1],"."));
        }else{
            touch(FileSystem_StoragePath.$this->lastblock.".".$this->suffix);
        }
    }
    
    //存储到文件系统当中去，返回值
    //块名称:起始位置:结束位置
    public function save($rawdata){
        $data = $this->compress($rawdata);
        $blockname = false;
        $start = 0;
        $end = 0;

        $lastfile = FileSystem_StoragePath.$this->lastblock.".".$this->suffix;
        clearstatcache();
        if(filesize($lastfile) < FileSystem_blockSize){
            $blockname = $this->lastblock;
            $start = filesize($lastfile);
        }else{
            $blockname = $this->lastblock+1;
        }
        $targetfile = FileSystem_StoragePath.$blockname.".".$this->suffix;
        $fd = fopen($targetfile,"ab");
        do{
            usleep(10);
        }while(!flock($fd,LOCK_EX));
        fseek($fd,$start);
        fwrite($fd,$data);
        fclose($fd);
        clearstatcache();
        $end = filesize($targetfile);
        return $blockname.":".$start.":".$end;
    }

    //通过块名称，起始和结束位置获取内容
    //参数 save的返回值
    public function read($param){
        $tmp =  explode(":",$param);
        $filename=FileSystem_StoragePath.$tmp[0].".".$this->suffix;
        $start = $tmp[1];
        $end = $tmp[2];
        $fd = fopen($filename,"rb");
        fseek($fd,$start);
        $data = fread($fd,$end-$start);
        fclose($fd);
        return $data?$this->uncompress($data):false;
    }
    
    //压缩方法
    protected function compress($data){
        if($this->compressmethod=="gzip"){
            return gzencode($data,$this->compresslevel);
        }else if($this->compressmethod=="deflate"){
            return gzdeflate($data,$this->compresslevel);
        }else if($this->compressmethod=="zlib"){
            return gzcompress($data,$this->compresslevel);
        }
        return  $data;
    }
    //解压方法
    protected function uncompress($data){
        if($this->compressmethod=="gzip"){
            return gzdecode($data);
        }else if($this->compressmethod=="deflate"){
            return gzinflate($data);
        }else if($this->compressmethod=="zlib"){
            return gzuncompress($data);
        }
        return  $data;
    }
}
