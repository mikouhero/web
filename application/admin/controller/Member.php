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


class Member extends Base
{
    public function index()
    {
        return view("admin@member/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.id'] = ['>','0'];
        if(isset($data['cid']) && !empty($data['cid'])){
            $condition['p1.cid'] = [ '=', $data['cid']];
        }

        if(isset($data['user']) && !empty($data['user'])){
            $condition['p1.user'] = [ '=', $data['user']];
        }

        $list = Db::name('member')
            ->alias('p1')
            ->field('p1.id,p1.user,p1.status,p2.id as cid,p2.code,p2.name')
            ->join('company p2','p1.cid = p2.id','left')
            ->where($condition)
            ->limit($start, $pagesize)
            ->select();
        $company = Db::name('company')->field('id,code,name')->select();
        $count = Db::name('member')->alias('p1')->where($condition)->count();
        $res = array(
            'memberList' => $list,
            'companyList' => $company,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['user']) || empty($data['user']) || !isset($data['cid']) || empty($data['cid']) || !isset($data['password']) || empty($data['password'])  ||  !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('member')->where('user', $data['user'])->count()) {
            $this->ajaxReturnMsg(202, '用户名已存在', '');
        }

        //加密
        $param = $data;
        $param['password'] = md5($data['password']);
        $param['create_time'] = date("Y-m-d H:i:s");

        $id = Db::name('member')->insertGetId($param);
        $this->ajaxReturnMsg(200, 'success', $id);

    }

    public function update(Request $request)
    {

        $input = $request->post();
        $data = json_decode($input['msg'], true);
        if (!isset($data['id']) || empty($data['id']) || !isset($data['user']) || empty($data['user']) || !isset($data['cid']) || empty($data['cid']) ||   !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }


        // 判断用户是否存在
        if (Db::name('member')->where('id', '<>', $data['id'])->where('user', $data['user'])->count()) {
            $this->ajaxReturnMsg(202, '用户名已存在', '');
        }

        $param = array(
            'user' => $data['user'],
            'cid' => $data['cid'],
            'status' => $data['status'],
        );
        Db::name('member')->where('id', $data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('member')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $flag = Db::name('member')->where('id',$data['id'])->delete();
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }

}