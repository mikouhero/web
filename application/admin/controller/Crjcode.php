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


class Crjcode extends Base
{
    public function index()
    {
        return view("admin@crjcode/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.deleted'] = ['=', '0'];
        if (isset($data['s_crj_code']) && !empty($data['s_crj_code'])) {
            $condition['p1.crj_code'] = ['=', $data['s_crj_code']];
        }

        if (isset($data['s_cid']) && !empty($data['s_cid'])) {
            $condition['p1.cid'] = ['=', $data['s_cid']];
        }

        $list = Db::name('crj_code')
            ->alias('p1')
            ->field('p1.id,
                     p1.cid,
                     p2.name as company_name ,
                     p1.crj_code
                     '
                    )
            ->join('company p2', 'p2.id = p1.cid', 'left')
            ->where($condition)
            ->order('id', 'desc')
            ->limit($start, $pagesize)
            ->select();
        $companyList = Db::name('company')->field('id,name')->select();

        $count = Db::name('crj_code')->alias('p1')
            ->join('company p2', 'p2.id = p1.cid', 'left')
            ->where($condition)->count();
        $res = array(
            'list' => $list,
            'companyList' => $companyList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['cid']) || empty($data['cid']) || !isset($data['crj_code']) || empty($data['crj_code'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }


        if (Db::name('crj_code')->where('crj_code', $data['crj_code'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

       Db::startTrans();
       try{
        $param = array(
            'cid' => $data['cid'],
            'crj_code' => $data['crj_code'],
            'create_time' => date("Y-m-d H:i:s")

        );
         Db::name('crj_code')->insertGetId($param);


        Db::commit();
        $this->ajaxReturnMsg(200, 'success', '');
       }catch (\Exception $e){
           Db::rollback();
           $this->ajaxReturnMsg(204, '添加失败', '');

       }


    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['id']) || empty($data['id']) || !isset($data['cid']) || empty($data['cid']) || !isset($data['crj_code']) || empty($data['crj_code'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('crj_code')->where('id', '<>', $data['id'])->where('crj_code', $data['crj_code'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        Db::startTrans();
        try {
            $param = array(
                'cid' => $data['cid'],
                'crj_code' => $data['crj_code'],

            );
            Db::name('crj_code')->where('id', $data['id'])->update($param);

            Db::commit();
            $this->ajaxReturnMsg(200, 'success', '');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturnMsg(204, '更新失败', '');

        }

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('crj_code')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $param = array(
            'deleted' => 1,
            'delete_time' => date("Y-m-d H:i:s")
        );
        Db::name('crj_code')->where('id', $data['id'])->update($param);

        $this->ajaxReturnMsg(200, 'success', '');
    }
}