<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 2018/9/9 0009
 * Time: 17:15
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;



class Loginlog extends Base
{
    public function index()
    {
        return view("admin@loginlog/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.source'] = ['=','0'];
        if(isset($data['name']) && !empty($data['name'])){
            $condition['p1.login_name'] = [ '=', $data['name']];
        }

        $list = Db::name('login_log')
            ->alias('p1')
            ->field('p1.id,p1.login_name,p1.create_time,p1.service_ip')
            ->where($condition)
            ->limit($start, $pagesize)
            ->select();
        $count = Db::name('login_log')->alias('p1')->where($condition)->count();
        $res = array(
            'logList' => $list,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

}