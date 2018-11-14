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



class Isp extends Base
{
    public function index()
    {
        return view("admin@isp/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.deleted'] = ['=','0'];
        if(isset($data['isp']) && !empty($data['isp'])){
            $condition['p1.isp'] = [ '=', $data['isp']];
        }

        if(isset($data['name']) && !empty($data['name'])){
            $condition['p1.isp_sales'] = [ '=', $data['name']];
        }

        $list = Db::name('channel')
            ->alias('p1')
            ->field('p1.id,p1.isp,p1.isp_sales,p1.phone')
            ->where($condition)
            ->limit($start, $pagesize)
            ->select();
        $threeList = Db::name('isp')->field('id,name')->where('deleted','0')->select();

        $count = Db::name('channel')->alias('p1')->where($condition)->count();
        $res = array(
            'list' => $list,
            'count' => ceil($count / $pagesize),
            'threeList'=>$threeList,
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['isp']) || empty($data['isp']) || !isset($data['isp_sales']) || empty($data['isp_sales']) || !isset($data['phone']) || empty($data['phone']) ) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (!preg_match('/^1[3456789]{1}\d{9}$/', $data['phone'])) {
            $this->ajaxReturnMsg(202, '手机号格式错误', '');
        }

        // 判断用户是否存在
//        if (Db::name('channel')->where('isp_sales', $data['isp_sales'])->count()) {
 //           $this->ajaxReturnMsg(203, '运营商经理已存在', '');
  //      }


        $param = array(
            'isp' =>$data['isp'],
            'isp_sales' =>$data['isp_sales'],
            'phone' =>$data['phone'],
            'create_time' => date('Y-m-d H:i:s'),
        );

        Db::name('channel')->insert($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['id']) || empty($data['id']) || !isset($data['isp']) || empty($data['isp']) || !isset($data['isp_sales']) || empty($data['isp_sales']) || !isset($data['phone']) || empty($data['phone']) ) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (!preg_match('/^1[3456789]{1}\d{9}$/', $data['phone'])) {
            $this->ajaxReturnMsg(202, '手机号格式错误', '');
        }

        // 判断用户是否存在
//        if (Db::name('channel')->where('id','<>',$data['id'])->where('isp_sales', $data['isp_sales'])->count()) {
//            $this->ajaxReturnMsg(203, '运营商经理已存在', '');
//        }
        $param = array(
            'isp' =>$data['isp'],
            'isp_sales' =>$data['isp_sales'],
            'phone' =>$data['phone'],
        );

        Db::name('channel')->where('id', $data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('channel')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $param = array(
            'deleted' => 1,
            'delete_time' => date("Y-m-d H:i:s")
        );
        $flag = Db::name('channel')->where('id',$data['id'])->update($param);
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}