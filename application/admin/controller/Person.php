<?php
/**
 * Created by PhpStorm.
 * User: Mikou.hu
 * Date: 2018/9/27
 */

namespace app\admin\controller;

use think\Db;
use think\Session;
use think\Request;

class Person extends Base
{
    public function index()
    {
        return view('admin@person/index');

    }

    public function  getList()
    {
        $user_msg = Session::get('manger_user');
        $data = Db::name('user')->where('id',$user_msg['id'])->find();
        $res = array();
        $res['ip'] = \think\Request::instance()->ip();;//登录ip地址
        $res['last_login_time'] = formatDate($user_msg['last_login_time']);
        $res['user'] = $data['user_name'];
        $res['phone'] = $data['mobile'];
        $res['create_time'] = $data['create_time'];

        $this->ajaxReturnMsg('200', 'success', $res);
    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['old']) || empty($data['old']) || !isset($data['new']) || empty($data['new']) || !isset($data['renew']) || empty($data['renew']) ) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if(!Db::name('user')->where('id',Session::get('manger_user.id'))->where('password',md5($data['old']))->find()){
            $this->ajaxReturnMsg(201, '原密码错误', '');
        }
        if(md5($data['new']) !== md5($data['renew'])){
            $this->ajaxReturnMsg(201, '两次密码不一致', '');
        }

        Db::name('user')->where('id',Session::get('manger_user.id'))->update(array('password'=>md5($data['new'])));

        Session::delete('manger_user');
        $this->ajaxReturnMsg(200, 'success', '');

    }
    public function logout()
    {
        Session::delete('manger_user');
        $this->redirect('/admin/login');
        die;
    }

}