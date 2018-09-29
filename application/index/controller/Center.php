<?php
/**
 * Created by PhpStorm.
 * User: Mikou.hu
 * Date: 2018/9/29
 */

namespace app\index\controller;


use think\Controller;
use think\Session;

class Center extends  Controller
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $css = config('style_path.css_path');
        $js = config('style_path.js_path');
        $img = config('style_path.img_path');
        $picture = config('style_path.picture_path');
        $this->assign(array(
            'css' => $css,
            'js' => $js,
            'img' => $img,
            'picture' => $picture,
            'on1' =>'',
            'on2' =>'',
            'on3' =>'',
            'on4' =>'',
        ));

        $request = \think\Request::instance();
        if (!$user_msg = Session::get('member_user')) {
            if($request->post()){
                $this->ajaxReturnMsg('105', '请重新登录', '');
            }
            $this->redirect('/');
        }
    }

    public function index()
    {
        return view('index@center/index');
    }

    public function pwd()
    {
        return view('index@center/pwd');

    }

    public function log()
    {
        return view('index@center/log');
    }

    public function order()
    {
        return view('index@center/order');

    }

    public function note()
    {
        return view('index@center/note');

    }

    protected function ajaxReturnMsg($code = 200, $msg, $data, $api_id = 0)
    {
        //        $this->api->end($api_id,$code,$msg,$data);
        header('Access-Control-Allow-Origin: *');//跨域
        header('Content-type: application/json');
        echo json_encode(array('code' => $code, 'msg' => $msg, 'data' => $data));
        die;
    }


}