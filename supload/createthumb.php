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

// 创建缩略图
$obj = new PicThumb($logfile);
$obj->set_config($config);
if($obj->create_thumb($source, $dest)){
    ob_clean();
    header('content-type:'.mime_content_type($dest));
    exit(file_get_contents($dest));
}

?>