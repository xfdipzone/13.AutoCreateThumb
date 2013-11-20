<?php

$thumb_config = array(

    'news' => array(
        'fromdir' => 'news', // 来源目录
        'type' => 'fit',
        'width' => 100,
        'height' => 100,
        'bgcolor' => '#FF0000'
    ),

    'news_1' => array(
        'fromdir' => 'news',
        'type' => 'fit',
        'width' => 200,
        'height' => 200,
        'bgcolor' => '#FFFF00'
    ),

    'article' => array(
        'fromdir' => 'article',
        'type' => 'crop',
        'width' => 250,
        'height' => 250,
        'watermark' => WWW_PATH.'/supload/watermark.png'
    )

);

?>