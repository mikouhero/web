<?php
/**
 * Created by PhpStorm.
 * User: Mikou.hu
 * Date: 2019/1/18
 */

namespace app\admin\controller;


use think\Controller;
use app\demo\PhpMailer;
use think\Db;
class Mail extends Controller
{

    private function sendMail($body)
    {
        $mailInfo = array(   //二维数组
//            'to_email' => 'chenmiao.li@fengyou.net.cn', //收件人邮箱
            'to_email' => '468094404@qq.com', //收件人邮箱
            'to_name' => '管理员', //收件人邮箱
            'subject' => '您有一封新的邮件提醒',
            'body'    => $body,
//            'body' => "<h2>你好</h2> 这是一个邮件,
//            <a href='http://www.baidu.com'>link</a>
//            <hr><img alt='这是一张图片' src='http://phpbasefiles.woaap.com/mz/upload/Product/201812/20181220103752.jpg'>",//图片src对应图片标识
//            'img' => array('path' => 'test/1.jpg', //可以使用相对路径
//                'cid' => 'test_id1', //附件内容标识
//                'name' => '1.jpg'
//            ),
//            'attachment' => array(
//                'path' => 'text/text.txt',
//                'name' => '附件.txt'
//            )
        );

        $mail = new PhpMailer(); //建立邮件发送类
        $mailConfig = config('MAIL_CONF');//获取mail配置

        $mail->CharSet = $mailConfig['MAIL_CHARSET'];
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->Host = $mailConfig['MAIL_HOST']; // 您的企业邮局域名
        $mail->SMTPAuth = $mailConfig['MAIL_SMTPAUTH']; // 启用SMTP验证功能
        $mail->Username = $mailConfig['MAIL_NAME']; // 邮局用户名(请填写完整的email地址)
        $mail->Password = $mailConfig['MAIL_PWD']; // 邮局密码
        $mail->Port = $mailConfig['MAIL_PORT'];
        $mail->From = $mailConfig['MAIL_NAME']; //邮件发送者email地址
        $mail->FromName = $mailConfig['MAIL_FROMNAME'];
        $mail->AddAddress($mailInfo['to_email'], $mailInfo['to_name']);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
        //$mail->AddReplyTo("", "");
        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式

        // $mail->addEmbeddedImage($mailInfo['img']['path'],$mailInfo['img']['cid'],$mailInfo['img']['name']);//图片路径,图片cid，图片名称
        //$mail->addAttachment($mailInfo['attachment']['path'],$mailInfo['attachment']['name']);//添加附件,并指定名称
        $mail->Subject = $mailInfo['subject']; //邮件标题
        $mail->Body = $mailInfo['body']; //邮件内容，上面设置HTML，则可以是HTML
        $flag = $mail->send();
        if (!$flag) {
            return  "邮件发送失败,错误原因: " . $mail->ErrorInfo;
        } else {
            // echo '邮箱发送成功';
            return 'ok';
        }
    }


    // 到提醒时间 更改状态  每天凌晨执行
    public function updateCrjPayStatus()
    {
        $param = array(
            'pay_status' => 0
        );
        Db::name('crj_bt')->where('deleted', 0)
            ->where(' DATE_FORMAT(pay_notice_time,"%Y-%m-%d") ',date("Y-m-d"))
            ->update($param);

        echo "ok";
        die;
    }

    // 发送邮件  每天8点执行

    public function getLog()
    {
        $data = Db::name('crj p1')->field("
                p1.crj,
                DATE_FORMAT(p2.pay_time,'%Y-%m-%d') as pay_time,
                p2.pay_account
                ")
            ->join('crj_bt p2','p2.crj_id = p1.id','left')
            ->where("now() Between  pay_notice_time and pay_time")
            ->where("pay_status",0)
            ->order("p2.pay_time")
            ->select();
        if($data){
            $body = "<h2>您好，你有一封新的邮件提醒</h2><br> ";
                foreach ($data as $k => $v){
                    $body .= (intval($k)+1)."、CRJ编号为<b style='color:red'>".$v['crj']."</b>的业务需要在<b style='color:red'>".$v['pay_time']."</b>付费，付款账号为<b style='color:red'>".$v['pay_account']."</b>请及时支付，并更新支付状态<br><br>";
                }
                $body .="发送时间:".date("Y-m-d H:i:s");
                $flag = $this->sendMail($body);
                echo $flag;die;
        }

        echo "no-send";die;
    }

}