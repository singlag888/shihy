<?php
// 源文件
$apk    = "fha_V1.0_2018-05-13-05-30_release.apk";
// 生成临时文件
$file   = tempnam("tmp", "zip");
// 复制文件
if(false===file_put_contents($file, file_get_contents($apk))){
    exit('copy faild!');
}
// 打开临时文件
$zip    = new ZipArchive();
$zip->open($file);
// 添加文件
// 由于apk限定只能修改此目录内的文件，否则会报无效apk包
$zip->addFromString('META-INF/extends.json', json_encode(array('author'=>'deeka')));
// 关闭zip
$zip->close();
// 下载文件
header("Content-Type: application/zip");
header("Content-Length: " . filesize($file));
header("Content-Disposition: attachment; filename=\"{$apk}\"");
// 输出二进制流
readfile($file);
// 删除临时文件
unlink($file);

?>