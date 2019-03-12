<?php

use app\demo\PhpMailer;
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


function mailSend($mailInfo){
    //date_default_timezone_set('Asia/Shanghai');//设置时区
    $mail =  new PhpMailer();
    echo "<pre>";
    $mailConfig = config('MAIL_CONF');//获取mail配置
    //dump($mailConfig);exit;
    //配置项
    $mail->isSMTP();
    $mail->Host = $mailConfig['MAIL_HOST'];
    $mail->Port = $mailConfig['MAIL_PORT'];//端口
    $mail->SMTPAuth = $mailConfig['MAIL_SMTPAUTH'];//启用SMTP认证
    $mail->CharSet = $mailConfig['MAIL_CHARSET'];
    $mail->Encoding = $mailConfig['MAIL_ENCODING'];
    $mail->Username = $mailConfig['MAIL_NAME']; //发送邮箱
    $mail->Password = $mailConfig['MAIL_PWD'];
    $mail->FromName = $mailConfig['MAIL_FROMNAME']; //发件人名字
    //内容
    $mail->addAddress($mailInfo['address']);//收件人邮箱
    $mail->Subject = $mailInfo['subject']; //邮件主题
    //图片以及附件
    $mail->isHTML(true); //支持html格式内容

    //最后一个参数可不写,默认为原文件名
//    $mail->addEmbeddedImage($mailInfo['img']['path'],$mailInfo['img']['cid'],$mailInfo['img']['name']);//图片路径,图片cid，图片名称
//    $mail->addAttachment($mailInfo['attachment']['path'],$mailInfo['attachment']['name']);//添加附件,并指定名称
    //邮件主体
    $mail->Body = $mailInfo['body'];//发送
    $mail->send();
     echo "错误原因: " . $mail->ErrorInfo;
die;
    return $mail->send()?true:false;
}

function sendMail($usermail,$code)
{
    $mail = new PhpMailer(); //建立邮件发送类
    $mail->CharSet = "UTF-8";
    $address ="290468335@qq.com";
    $mail->IsSMTP(); // 使用SMTP方式发送
    $mail->Host = "smtp.qq.com"; // 您的企业邮局域名
    $mail->SMTPAuth = true; // 启用SMTP验证功能
    $mail->Username = "290468335@qq.com"; // 邮局用户名(请填写完整的email地址)
    $mail->Password = "syaeymabqsfgbijj"; // 邮局密码
    $mail->Port=587;
    $mail->From = "290468335@qq.com"; //邮件发送者email地址
    $mail->FromName = "小钉铛ASK";
    $mail->AddAddress($usermail, "title");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
    //$mail->AddReplyTo("", "");

    //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
    $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

    $mail->Subject = "您的验证码是:"; //邮件标题
    $mail->Body =  "<h2>你好</h2> 这是一个邮件,
             <a href='#'>http://www.test.com</a>
            <hr><img alt='这是一张图片' src='cid:test_id1'>"; //邮件内容，上面设置HTML，则可以是HTML

    if(!$mail->Send())
    {
        // echo "邮件发送失败. <p>";
        // echo "错误原因: " . $mail->ErrorInfo;
        return false;
        exit;
    }else{

        // echo '邮箱发送成功';
        return '邮箱发送成功';
    }

}
