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


class Crjop extends Base
{
    public function index()
    {
        return view("admin@crjop/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.deleted'] = ['=', '0'];
        if (isset($data['s_crj']) && !empty($data['s_crj'])) {
            $condition['p1.crj'] = ['=', $data['s_crj']];
        }

        if (isset($data['s_cid']) && !empty($data['s_cid'])) {
            $condition['p1.cid'] = ['=', $data['s_cid']];
        }
        if (isset($data['s_isp']) && !empty($data['s_isp'])) {
            $condition['p1.isp'] = ['=', $data['s_isp']];
        }

        if (isset($data['s_speed']) && !empty($data['s_speed'])) {
            $condition['p1.speed'] = ['=', $data['s_speed']];
        }

        if (isset($data['s_status']) && !empty($data['s_status'])) {
            $condition['p1.status'] = ['=', $data['s_status']];
        }

        if (isset($data['s_isp_manager']) && !empty($data['s_isp_manager'])) {
            $condition['p5.id'] = ['=', $data['s_isp_manager']];
        }
        if (isset($data['s_out']) && !empty($data['s_out'])) {
            if ($data['s_out'] == 1) {
                $condition['p1.end_time'] = array(['<>',null],['>=', date('Y-m-d')]);
            }
            if ($data['s_out'] == 2) {
                $condition['p1.end_time'] = array(['<>',null],['<', date('Y-m-d')]);
            }
        }

        if (isset($data['s_time']) && !empty($data['s_time'])) {
            $condition['p1.end_time'] = array(['>=', date("Y-m-d")], ['<=', date("Y-m-d", strtotime('+' . $data['s_time'] . 'days'))]);
        }
        $list = Db::name('crj')
            ->alias('p1')
            ->field('p1.id,
                     p2.name as company_name,
                     p1.crj,
                     p1.address,
                     p3.id as Iid,
                     p3.port,
                     p3.ip,
                     p3.account,
                     p3.password,
                     p5.phone,
                     p5.isp_sales
                     '
                    )
            ->join('crj_code p6','p6.crj_code=p1.crj','left')
            ->join('company p2', 'p2.id = p6.cid', 'left')
            ->join('crj_op p3', 'p3.crj_id = p1.id', 'left')
            ->join('crj_bt p4','p4.crj_id = p1.id','left')
            ->join('channel p5','p5.id = p4.isp_manager','left')
            ->where('p3.crj_id is not null ')
            ->where($condition)
            ->order('id', 'desc')
            ->limit($start, $pagesize)
            ->select();
        $typeList = Db::name('service_type')->field('id,name,status')->select();
        $companyList = Db::name('company')->field('id,name')->select();
        $buildingList = Db::name('building')->field('id,name')->select();
        $ispList = Db::name('channel')->field('id,isp_sales')->select();
        $crjList = Db::name('crj')
            -> alias('p1')
            ->field('p1.id,p1.crj')
            ->join('crj_op p2','p1.id = p2.crj_id','left')
            ->where('p2.crj_id is null')
            ->where('p1.deleted',0)
            ->select();
        $count = Db::name('crj')->alias('p1')
            ->join('crj_code p6','p6.crj_code=p1.crj','left')
            ->join('company p2', 'p2.id = p6.cid', 'left')
            ->join('crj_op p3', 'p3.crj_id = p1.id', 'left')
            ->where($condition)->count();
        $res = array(
            'list' => $list,
            'typeList' => $typeList,
            'companyList' => $companyList,
            'buildingList' => $buildingList,
            'ispList' => $ispList,
            'crjList'  => $crjList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['crj_id']) || empty($data['crj_id']) || !isset($data['port']) || empty($data['port']) || !isset($data['ip']) || empty($data['ip']) || !isset($data['account']) || empty($data['account']) || !isset($data['password']) || empty($data['password'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (Db::name('crj_op')->where('crj_id', $data['crj_id'])->count()) {
            $this->ajaxReturnMsg(203, '该编号的运维信息已存在', '');
        }

       Db::startTrans();
       try{


        $param2 = array(
            'crj_id' => $data['crj_id'],
            'port' => $data['port'],
            'ip' => $data['ip'],
            'account' => $data['account'],
            'password' => $data['password'],
        );

        Db::name('crj_op')->insert($param2);

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

        if (!isset($data['Iid']) || empty($data['Iid']) || !isset($data['crj']) || empty($data['crj']) || !isset($data['port']) || empty($data['port']) || !isset($data['ip']) || empty($data['ip']) || !isset($data['account']) || empty($data['account']) || !isset($data['password']) || empty($data['password'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('crj')->where('id', '<>', $data['id'])->where('crj', $data['crj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        Db::startTrans();
        try {

            $param2 = array(
                'port' => $data['port'],
                'ip' => $data['ip'],
                'account' => $data['account'],
                'password' => $data['password'],
            );

            Db::name('crj_op')->where('crj_id', $data['Iid'])->update($param2);

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
        $falg = Db::name('crj')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $param = array(
            'deleted' => 1,
            'delete_time' => date("Y-m-d H:i:s")
        );
        $flag = Db::name('crj_op')->where('id', $data['id'])->update($param);
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}