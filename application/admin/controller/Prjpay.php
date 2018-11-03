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


class Prjpay extends Base
{
    public function index()
    {
        return view("admin@prjpay/index");
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
            p3.pre_cost,
            p1.final_cost,
            p3.first_pay,
            p3.second_pay,
            p3.last_pay,
            p1.fj')
            ->join('company p2', 'p2.id = p1.cid', 'left')
            ->join('prj_bt p3','p3.prj_id = p1.id','left')
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

}