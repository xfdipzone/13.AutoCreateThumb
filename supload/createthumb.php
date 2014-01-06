<?php
define('WWW_PATH', dirname(dirname(__FILE__))); // 站点www目录

require(WWW_PATH.'/PicThumb.class.php'); // include PicThumb.class.php
require(WWW_PATH.'/ThumbConfig.php');    // include ThumbConfig.php

$logfile = WWW_PATH.'/createthumb.log';  // 日志文件
$source_path = WWW_PATH.'/upload/';      // 原路径
$dest_path = WWW_PATH.'/supload/';       // 目标路径

$path = isset($_GET['path'])? $_GET['path'] : '';     // 访问的图片URL

// 检查path
if(!$path){
    exit();
}

// 获取图片URI
$relative_url = str_replace($dest_path, '', WWW_PATH.$path);

// 获取type
$type = substr($relative_url, 0, strpos($relative_url, '/'));

// 获取config
$config = isset($thumb_config[$type])? $thumb_config[$type] : '';

// 检查config
if(!$config || !isset($config['fromdir'])){
    exit();
}

// 原图文件
$source = str_replace('/'.$type.'/', '/'.$config['fromdir'].'/', $source_path.$relative_url);

// 目标文件 
$dest = $dest_path.$relative_url;

if(!file_exists($source)){ // 原图不存在
    exit();
}

// 高并发处理
$processing_flag = '/tmp/thumb_'.md5($dest); // 用于判断文件是否处理中
$is_wait = 0;                                // 是否需要等待
$wait_timeout = 5;                           // 等待超时时间

if(!file_exists($processing_flag)){
    file_put_contents($processing_flag, 1, true);
}else{
    $is_wait = 1;
}

if($is_wait){ // 需要等待生成
    while(file_exists($processing_flag)){
        if(time()-$starttime>$wait_timeout){ // 超时
            exit();
        }
        usleep(300000); // sleep 300 ms
    }

    if(file_exists($dest)){ // 图片生成成功
        ob_clean();
        header('content-type:'.mime_content_type($dest));
        exit(file_get_contents($dest));
    }else{
        exit(); // 生成失败退出
    }
}

// 创建缩略图
$obj = new PicThumb($logfile);
$obj->set_config($config);
$create_flag = $obj->create_thumb($source, $dest);

unlink($processing_flag); // 删除处理中标记文件

if($create_flag){ // 判断是否生成成功
    ob_clean();
    header('content-type:'.mime_content_type($dest));
    exit(file_get_contents($dest));
}

?>