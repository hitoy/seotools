<!DOCTYPE HTML>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>文件上传</title>
<head>
<body>
<?php
if(!empty($_FILES)){
    $f = $_FILES['f'];
    if(move_uploaded_file($f['tmp_name'], dirname(__FILE__).'/'.$f['name'])){
        echo '上传成功';
    }else{
        echo '上传失败';
    }
}
?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="f">
        <input type="submit" value="提交">
    </form>
</body>
</html>
