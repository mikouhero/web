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


class Crjnotice extends Base
{
    public function index()
    {
        return view("admin@crjnotice/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p4.deleted'] = ['=', '0'];


        // 支付状态
        if (isset($data['s_status']) && in_array($data['s_status'],[0,1])) {
            $condition['p4.pay_status'] = ['=', $data['s_status']];
        }
        if($data['s_status'] === ''){
            unset($condition['p4.pay_status']);
        }

        // crj 编号
        if (isset($data['s_crj']) && !empty($data['s_crj'])) {
            $condition['p3.crj_code'] = ['=', $data['s_crj']];
        }

        // 业务类型
        if (isset($data['s_type']) && !empty($data['s_type'])) {
            $condition['p1.type'] = ['=', $data['s_type']];
        }

        // 运营商
        if (isset($data['s_isp_manager']) && !empty($data['s_isp_manager'])) {
            $condition['p5.id'] = ['=', $data['s_isp_manager']];
        }

        $list = Db::name('crj')
            ->alias('p1')
            ->field('
                     p1.id ,
                     p3.crj_code as crj,
                     p1.type,
                     p4.isp_method,
                     p4.price as s_price,
                     DATE_FORMAT(p4.pay_time,"%Y-%m-%d") as pay_time,
                     DATE_FORMAT(p4.pay_notice_time,"%Y-%m-%d") as pay_notice_time,
                     p4.pay_status,
                     p4.pay_account,
                     p5.isp
                     '
                    )
            ->join('crj_code p3', 'p1.crj = p3.crj_code', 'left')
            ->join('crj_bt p4','p4.crj_id = p1.id','left')
            ->join('channel p5','p5.id = p4.isp_manager','left')
            ->where($condition)
            ->order('p1.id', 'desc')
            ->limit($start, $pagesize)
            ->select();
        $typeList = Db::name('service_type')->field('id,name,status')->select();
        $ispList = Db::name('channel')->field('id,concat(isp,"(",isp_sales,")") as isp_sales')->select();


        $count = Db::name('crj')->alias('p1')
            ->join('crj_code p3', 'p3.crj_code = p1.crj', 'left')
            ->join('crj_bt p4','p4.crj_id = p1.id','left')
            ->join('channel p5','p5.id = p4.isp_manager','left')
            ->where($condition)->count();
        $res = array(
            'list' => $list,
            'typeList' => $typeList,
            'ispList' => $ispList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }



    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (
            !isset($data['crj']) || empty($data['crj']) ||
            !isset($data['pay_time']) || empty($data['pay_time']) ||
            !isset($data['pay_notice_time'])  ||
            !isset($data['pay_account']) || empty($data['pay_account'])
        ) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }
        if(empty($data['pay_notice_time'])){
            $data['pay_notice_time'] = date('Y-m-d' ,strtotime("-5 days",strtotime($data['pay_time'])));
        }


        Db::startTrans();
        try {
            $param = array(
                'pay_time' => $data['pay_time'],
                'pay_notice_time' => $data['pay_notice_time'],
                'pay_account' => $data['pay_account'],
            );
            Db::name('crj_bt')->where('crj_id', $data['id'])->update($param);


            Db::commit();
            $this->ajaxReturnMsg(200, 'success', '');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturnMsg(204, '更新失败', '');

        }

    }

    public function updateStatus(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }

        $falg = Db::name('crj')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $msg = Db::name('crj_bt')->field("isp_method,pay_time,pay_notice_time")->where('crj_id', $data['id'])->find();

        if($msg['isp_method'] == 1){
            $pay_time= date("Y-m-d",strtotime("+1 months",strtotime($msg['pay_time'])));
            $pay_notice_time= date("Y-m-d",strtotime("+1 months",strtotime($msg['pay_notice_time'])));
        }else if($msg['isp_method'] == 2){

            $pay_time= date("Y-m-d",strtotime("+3 months",strtotime($msg['pay_time'])));
            $pay_notice_time= date("Y-m-d",strtotime("+3 months",strtotime($msg['pay_notice_time'])));

        }else if($msg['isp_method'] == 3) {
            $pay_time= date("Y-m-d",strtotime("+1 years",strtotime($msg['pay_time'])));
            $pay_notice_time= date("Y-m-d",strtotime("+1 years",strtotime($msg['pay_notice_time'])));
        }else{
            $pay_time= '';
            $pay_notice_time='';
        }
        $param = array(
            'pay_status' => 1,
            'pay_time' =>$pay_time,
            'pay_notice_time' => $pay_notice_time
        );

        $flag = Db::name('crj_bt')->where('crj_id', $data['id'])->update($param);

        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}