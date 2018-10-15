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


class Prj extends Base
{
    public function index()
    {
        return view("admin@prj/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.deleted'] = ['=', '0'];
        if (isset($data['s_prj']) && !empty($data['s_prj'])) {
            $condition['p1.prj'] = ['=', $data['s_prj']];
        }

        if (isset($data['s_prj_user']) && !empty($data['s_prj_user'])) {
            $condition['p1.prj_user'] = ['=', $data['s_prj_user']];
        }

        if (isset($data['s_prj_manger']) && !empty($data['s_prj_manger'])) {
            $condition['p1.prj_manger'] = ['=', $data['s_prj_manger']];
        }

        if (isset($data['s_cid']) && !empty($data['s_cid'])) {
            $condition['p1.cid'] = ['=', $data['s_cid']];
        }

        $list = Db::name('prj')
            ->alias('p1')
            ->field('p1.id,
            p1.cid,
            p2.name as company_name ,
            p1.prj,p1.address,
            p1.pro_name,
            p1.team,
            p1.prj_user,
            p1.prj_manger,
            p1.pre_cost,
            p1.final_cost,
            p1.first_pay,
            p1.second_pay,
            p1.last_pay')
            ->join('company p2', 'p2.id = p1.cid', 'left')
            ->where($condition)
            ->order('id', 'desc')
            ->limit($start, $pagesize)
            ->select();
        $companyList = Db::name('company')->field('id,name')->select();

        $count = Db::name('prj')->alias('p1')->where($condition)->count();
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

        if (!isset($data['cid']) || empty($data['cid']) || !isset($data['prj']) || empty($data['prj']) || !isset($data['address']) || empty($data['address']) || !isset($data['pro_name']) || empty($data['pro_name'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('prj')->where('prj', $data['prj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        if ($data['prj'] != $this->getCode()) {
            $this->ajaxReturnMsg(204, '非法的业务编号', '');
        }

        Db::startTrans();
        try {
            $param = array(
                'cid' => $data['cid'],
//                'prj' => $data['prj'],
                'pro_name' => $data['pro_name'],
                'address' => $data['address'],
                'team' => $data['team'],
                'prj_user' => $data['prj_user'],
                'prj_manger' => $data['prj_manger'],
                'pre_cost' => $data['pre_cost'],
                'final_cost' => $data['final_cost'],
                'first_pay' => $data['first_pay'],
                'second_pay' => $data['second_pay'],
                'last_pay' => $data['last_pay'],
                'create_time' => date("Y-m-d H:i:s")
            );
            Db::name('prj')->insertGetId($param);

            Db::commit();
            $this->ajaxReturnMsg(200, 'success', '');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturnMsg(204, '添加失败', '');

        }


    }

    /*
     * 获取prj号码
     */
    public function add()
    {
        $prj = $this->getCode();

        $this->ajaxReturnMsg(200, 'success', $prj);
    }

    private  function getCode()
    {
        $code = Db::name('prj')->field('prj')->limit(1)->order('id','desc')->find();

        if(empty($code['prj'])){
            $prj = "prj" . "10001";
        }else{
            $prj = "prj" . (intval( substr($code['prj'],3)) + 1);
        }
        return $prj;
    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['id']) || empty($data['id']) || !isset($data['cid']) || empty($data['cid']) || !isset($data['prj']) || empty($data['prj']) || !isset($data['address']) || empty($data['address']) || !isset($data['cid']) || empty($data['cid']) || !isset($data['pro_name']) || empty($data['pro_name']) ) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }


        // 判断用户是否存在
        if (Db::name('prj')->where('id', '<>', $data['id'])->where('prj', $data['prj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        Db::startTrans();
        try {
            $param = array(
                'cid' => $data['cid'],
//                'prj' => $data['prj'],
                'pro_name' => $data['pro_name'],
                'address' => $data['address'],
                'team' => $data['team'],
                'prj_user' => $data['prj_user'],
                'prj_manger' => $data['prj_manger'],
                'pre_cost' => $data['pre_cost'],
                'final_cost' => $data['final_cost'],
                'first_pay' => $data['first_pay'],
                'second_pay' => $data['second_pay'],
                'last_pay' => $data['last_pay'],
            );
            Db::name('prj')->where('id', $data['id'])->update($param);

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
        $falg = Db::name('prj')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $param = array(
            'deleted' => 1,
            'delete_time' => date("Y-m-d H:i:s")
        );
        $flag = Db::name('prj')->where('id', $data['id'])->update($param);
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}