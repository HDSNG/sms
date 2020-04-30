<?php
/**
 * 调用测试用例
 * User: Administrator
 * Date: 2020/4/30 0030
 * Time: 10:17
 */

require "../Base.php";
require "../Boot.php";
require "../Common.php";

//机器人通知
$array = [
    'system_code'=>'dianba_public',
    'secret_key'=>'dianba_public',
    'level'=>'2',
    'channel'=>'1',
    'robot_name'=>'电霸公共01',
];
$boot  = new Damow\Boot($array);
$content = [
    'type'=>1,
    'content'=>'{     "msgtype": "text",     "text": {         "content": "广州今日天气：29度，大部分多云，降雨概率：60%" } }',
];
$boot->PushMsg($content);