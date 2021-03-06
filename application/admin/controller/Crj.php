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
use think\Session;


class Crj extends Base
{
    public function index()
    {
        return view("admin@crj/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.deleted'] = ['=', '0'];
        if (isset($data['s_crj']) && !empty($data['s_crj'])) {
            $condition['p3.crj_code'] = ['=', $data['s_crj']];
        }

        if (isset($data['s_cid']) && !empty($data['s_cid'])) {
            $condition['p3.cid'] = ['=', $data['s_cid']];
        }
//        if (isset($data['s_isp']) && !empty($data['s_isp'])) {
//            $condition['p1.isp'] = ['=', $data['s_isp']];
//        }

        if (isset($data['s_type']) && !empty($data['s_type'])) {
            $condition['p1.type'] = ['=', $data['s_type']];
        }

        if (isset($data['s_address']) && !empty($data['s_address'])) {
            $condition['p1.address'] = ['like', '%'.$data['s_address'].'%'];
        }


        if (isset($data['s_status']) && !empty($data['s_status'])) {
//            $condition['p1.status'] = ['=', $data['s_status']];
            if($data['s_status'] ==3){
                $condition['p1.deleted'] = ['=', 1];
            }
        }

        if (isset($data['s_isp_manager']) && !empty($data['s_isp_manager'])) {
            $condition['p5.id'] = ['=', $data['s_isp_manager']];
        }
        if (isset($data['s_status']) && !empty($data['s_status'])) {
            if ($data['s_status'] == 1) {
                $condition['p1.end_time'] = ['>=', date('Y-m-d')];
            }
            if ($data['s_status'] == 2) {
                $condition['p1.end_time'] = ['<', date('Y-m-d')];
            }
        }

        if (isset($data['s_time']) && !empty($data['s_time'])) {
            $condition['p1.end_time'] = array(['>=', date("Y-m-d")], ['<=', date("Y-m-d", strtotime('+' . $data['s_time'] . 'days'))]);
        }
        $user = Session::get('manger_user');
        /**
         * 拿到user_id 判断是否是业务员
         */
        $limit = Db::name('user')
            ->alias('p1')
            ->join('user_role p2','p2.user_id=p1.id','left')
            ->join('role p3','p2.role_id = p3.id','left')
            ->where('p3.name','=','业务员')
            ->where('p1.id','=',$user['id'])
            ->count();
        if($limit){
            $condition['p4.sales'] = ['=', $user['id']];
        }
        $list = Db::name('crj')
            ->alias('p1')
            ->field('p1.id,
                     p2.name as company_name ,
                     p3.crj_code as crj,
                     p1.address,
                     p1.type,
                     p1.demand,
                     p1.method,
                     p1.price,
                     p1.status,
                     p1.deleted,
                     DATE_FORMAT(p1.start_time,"%Y-%m-%d") as start_time,
                     DATE_FORMAT(p1.end_time,"%Y-%m-%d") as end_time,
                     DATE_FORMAT(p1.teardown,"%Y-%m-%d") as teardown,
                     p6.name as sales,
                     p6.id as sales_id
                     '
                    )
            ->join('crj_code p3', 'p1.crj = p3.crj_code', 'left')
            ->join('company p2', 'p2.id = p3.cid', 'left')
            ->join('crj_bt p4','p4.crj_id = p1.id','left')
            ->join('user p6','p6.id=p4.sales','left')
//            ->join('channel p5','p5.id = p4.isp_manager','left')
            ->where($condition)
            ->order('id', 'desc')
            ->limit($start, $pagesize)
            ->select();
        $typeList = Db::name('service_type')->field('id,name,status,deleted')->select();
        $companyList = Db::name('company')->field('id,name,deleted')->select();
        $buildingList = Db::name('building')->field('id,name')->select();
        $ispList = Db::name('channel')->field('id,isp_sales')->select();
//        $threeList = Db::name('isp')->field('id,name')->where('deleted','0')->select();
        /**
         * 业务员列表
         */
        $saleList = Db::name('user')
            ->field('p1.id,p1.name')
            ->alias('p1')
            ->join('user_role p2','p2.user_id=p1.id','left')
            ->join('role p3','p2.role_id = p3.id','left')
            ->where('p3.name','=','业务员')
            ->select();


        $count = Db::name('crj')->alias('p1')
            ->join('crj_code p3', 'p1.crj = p3.crj_code', 'left')
            ->join('company p2', 'p2.id = p3.cid', 'left')
            ->join('crj_bt p4','p4.crj_id = p1.id','left')
            ->join('user p6','p6.id=p4.sales','left')
//            ->join('channel p5','p5.id = p4.isp_manager','left')
            ->where($condition)->count();
        $res = array(
            'list' => $list,
            'typeList' => $typeList,
            'companyList' => $companyList,
            'buildingList' => $buildingList,
            'ispList' => $ispList,
//            'threeList'=>$threeList,
            'saleList'   => $saleList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);
        if (
            !isset($data['crj']) || empty($data['crj']) ||
            !isset($data['address']) || empty($data['address'])  ||
            !isset($data['method']) || empty($data['method']) ||
            !isset($data['price']) || empty($data['price']) ||
            !isset($data['demand']) || empty($data['demand']) ||
            !isset($data['sales_id']) || empty($data['sales_id']) ||
            !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if(empty($data['start_time'])){
            $data['start_time'] = date("Y-m-d");
        }
        if(empty($data['end_time'])){
            $data['end_time'] = date("Y-m-d",strtotime('1 years',strtotime($data['start_time'])));
        }
//
        if(empty($data['teardown'])){
            $data['teardown'] = NUll;
        }

        if (Db::name('crj')->where('crj', $data['crj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

       Db::startTrans();
       try{
        $param = array(
//            'cid' => $data['cid'],
            'crj' => $data['crj'],
            'type' => $data['type'],
            'address' => $data['address'],
//            's_price' => $data['s_price'],
            'price' => $data['price'],
//            'isp' => $data['isp'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => $data['status'],
//            'teardown' => $data['teardown'],
            'demand' => $data['demand'],
//            'actual' => $data['actual'],
            'method' => $data['method'],
//            'isp_method' => $data['isp_method'],
            'create_time' => date("Y-m-d H:i:s")

        );
        $crj_id = Db::name('crj')->insertGetId($param);

        $param3 = array(
            'crj_id' => $crj_id,
//            'isp_manager'=> $data['isp_manager'],
            'sales' =>$data['sales_id']
        );

        Db::name('crj_bt')->insert($param3);

        Db::commit();
        $this->ajaxReturnMsg(200, 'success', '');
       }catch (\Exception $e){
           Db::rollback();
           $this->ajaxReturnMsg(204, '添加失败', '');

       }


    }

    /*
     * 获取brj号码
     */
    public function add()
    {
        $crj = $this->getCode();

        $this->ajaxReturnMsg(200, 'success', $crj);
    }

    private  function getCode()
    {
        $code = Db::name('crj_code')
            ->field('p1.crj_code')
            ->alias('p1')
            ->join('crj p2','p2.crj = p1.crj_code' ,'left')
            ->where('p2.crj is null')
            ->select();
        $arr = array();
        if($code){
            foreach ($code as $k=>$v){
                $arr[] = $v['crj_code'];
            }
        }
        return $arr;
    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (
//            !isset($data['id']) || empty($data['id']) ||
//            !isset($data['cid']) || empty($data['cid']) ||
            !isset($data['crj']) || empty($data['crj']) ||
            !isset($data['address']) || empty($data['address']) ||
            !isset($data['method']) || empty($data['method']) ||
//            !isset($data['isp_method']) || empty($data['isp_method']) ||
//            !isset($data['s_price']) || empty($data['s_price']) ||
            !isset($data['price']) || empty($data['price']) ||
            !isset($data['demand']) || empty($data['demand']) ||
            !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }
        if(empty($data['end_time'])){
            $data['end_time'] = NUll;
        }

        if(empty($data['start_time'])){
            $data['start_time'] = NUll;
        }

        if(empty($data['teardown'])){
            $data['teardown'] = NUll;
        }

        // 判断用户是否存在
        if (Db::name('crj')->where('id', '<>', $data['id'])->where('crj', $data['crj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        Db::startTrans();
        try {
            $param = array(
//                'cid' => $data['cid'],
//                'crj' => $data['crj'],
                'type' => $data['type'],
                'address' => $data['address'],
//                's_price' => $data['s_price'],
                'price' => $data['price'],
//                'isp' => $data['isp'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => $data['status'],
                'teardown' => $data['teardown'],
                'demand' => $data['demand'],
//                'isp_method' => $data['isp_method'],
//                'actual' => $data['actual'],
                'method' => $data['method'],
            );
            Db::name('crj')->where('id', $data['id'])->update($param);


            $param3 = array(
//                'isp_manager'=> $data['isp_manager'],
                'sales' =>$data['sales_id']
            );

            Db::name('crj_bt')->where('crj_id',$data['id'])->where('deleted',0)->update($param3);

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
            'status'  => 3,
            'delete_time' => date("Y-m-d H:i:s")
        );
        Db::name('crj')->where('id', $data['id'])->update($param);
//        unset($param['status']);
//        $flag = Db::name('crj_bt')->where('crj_id', $data['id'])->update($param);
//
//        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}