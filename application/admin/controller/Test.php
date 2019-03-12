<?php
/**
 * Created by PhpStorm.
 * User: Mikou.hu
 * Date: 2019/1/18
 */

namespace app\admin\controller;

use think\Controller;
use app\demo\PhpMailer;


class Test extends Controller
{
    public function index()
    {
        $mailInfo = array(   //二维数组
            'address' => '784756024@qq.com', //收件人邮箱
            'subject' => '这里是邮件主题',
            'body' => "<h2>你好</h2> 这是一个邮件,
             <a href='#'>http://www.test.com</a>
            <hr><img alt='这是一张图片' src='cid:test_id1'>",//图片src对应图片标识
            'img' => array('path' => 'test/1.jpg', //可以使用相对路径
                'cid' => 'test_id1', //附件内容标识
                'name' => '1.jpg'
            ),
            'attachment' => array(
                'path' => 'text/text.txt',
                'name' => '附件.txt'
            )
        );
            $m = mailSend($mailInfo);
            var_dump($m);
die;
        $d = sendMail('mikouhero@163.com',1);
        var_dump($d);
    }


    public function sendMail()
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

}