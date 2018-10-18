<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function base64_image_content($base64_image_content, $path)
{
    //匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        $new_file = $path . "/" . date('Ymd', time()) . "/";
        if (!file_exists($new_file)) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0777, true);
        }
        $new_file = $new_file . time() . ".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            return $new_file;
        } else {
            return false;
        }
    } else {
        return false;
    }

}





/**
 * Decription :格式化时间
 * @param string $datetime
 * return false|string
 * @author: Mikou.hu
 * Date: 2018/9/29
 */
function formatDate($datetime = '')
{
    if (empty($datetime)) return '';
    $now_time = time();                                           //此刻时间
    $today_time = strtotime(date("Y-m-d"));                       //今天零点
    $yesday_time = strtotime(date("Y-m-d", strtotime("-1 day")));  //昨天零点
    $publish_time = strtotime($datetime);                         //发布时间

    if ($publish_time < $yesday_time) {
        $result = date('n月j日', $publish_time);
    } elseif ($publish_time >= $yesday_time && $publish_time < $today_time) {
        $result = '昨天 ' . date('G:i', $publish_time);
    } else {
        $diff_time = $now_time - $publish_time;
        if ($diff_time < 60) {
            $result = "1分钟前";
        } elseif ($diff_time >= 60 && $diff_time < 3600) {
            $result = floor($diff_time / 60) . "分钟前";
        } else {
            $result = floor($diff_time / 3600) . "小时前";
        }
    }
    return $result;
}
