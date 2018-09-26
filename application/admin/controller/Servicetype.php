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



class Servicetype extends Base
{
    public function index()
    {
        return view("admin@servicetype/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.id'] = ['>','0'];
        if(isset($data['name']) && !empty($data['name'])){
            $condition['p1.name'] = [ '=', $data['name']];
        }

        $list = Db::name('service_type')
            ->alias('p1')
            ->field('p1.id,p1.name,p1.status,p1.create_time')
            ->where($condition)
            ->limit($start, $pagesize)
            ->select();

        $count = Db::name('service_type')->alias('p1')->where($condition)->count();
        $res = array(
            'list' => $list,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);


        if (!isset($data['name']) || empty($data['name']) || !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('company')->where('name', $data['name'])->count()) {
            $this->ajaxReturnMsg(202, '类型已存在', '');
        }

        $param = array(
            'name' =>$data['name'],
            'status' =>$data['status'],
            'create_time' =>date('Y-m-d H:i:s')
        );

        Db::name('service_type')->insert($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);
        if (!isset($data['id']) || empty($data['id']) || !isset($data['name']) || empty($data['name']) || !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('service_type')->where('id', '<>', $data['id'])->where('name', $data['name'])->count()) {
            $this->ajaxReturnMsg(202, '编号已经存在', '');
        }
        $param = array(
            'name' => $data['name'],
            'status' => $data['status'],
        );
        Db::name('service_type')->where('id', $data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('service_type')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $param = array(
            'status' => 0,
        );
        Db::name('service_type')->where('id',$data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');
    }
}