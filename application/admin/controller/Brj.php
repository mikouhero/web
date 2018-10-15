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


class Brj extends Base
{
    public function index()
    {
        return view("admin@brj/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.deleted'] = ['=', '0'];
        if (isset($data['s_brj']) && !empty($data['s_brj'])) {
            $condition['p1.brj'] = ['=', $data['s_brj']];
        }

        if (isset($data['s_type']) && !empty($data['s_type'])) {
            $condition['p1.type'] = ['=', $data['s_type']];
        }

        if (isset($data['s_cid']) && !empty($data['s_cid'])) {
            $condition['p1.cid'] = ['=', $data['s_cid']];
        }
        if (isset($data['s_bid']) && !empty($data['s_bid'])) {
            $condition['p1.bid'] = ['=', $data['s_bid']];
        }

        if (isset($data['s_speed']) && !empty($data['s_speed'])) {
            $condition['p1.speed'] = ['=', $data['s_speed']];
        }
        if (isset($data['s_contact']) && !empty($data['s_contact'])) {
            $condition['p1.contact'] = ['=', $data['s_contact']];
        }

        if (isset($data['s_phone']) && !empty($data['s_phone'])) {
            $condition['p1.phone'] = ['=', $data['s_phone']];
        }

        if (isset($data['s_status']) && !empty($data['s_status'])) {
            $condition['p1.status'] = ['=', $data['s_status']];
        }
        if (isset($data['s_out']) && !empty($data['s_out'])) {
            if ($data['s_out'] == 1) {
                $condition['p1.end_time'] = ['>=', date('Y-m-d')];
            }
            if ($data['s_out'] == 2) {
                $condition['p1.end_time'] = ['<', date('Y-m-d')];
            }
        }

        if (isset($data['s_time']) && !empty($data['s_time'])) {
            $condition['p1.end_time'] = array(['>=', date("Y-m-d")], ['<=', date("Y-m-d", strtotime('+' . $data['s_time'] . 'days'))]);
        }
        $list = Db::name('brj')
            ->alias('p1')
            ->field('p1.id,
            p1.cid,
            p2.name as company_name ,
            p1.brj,p1.address,
            p1.type,
            p1.speed,
            p1.s_price,
            p1.contact,
            p1.phone,
            p1.status,
            DATE_FORMAT(p1.start_time,"%Y-%m-%d") as start_time,
            DATE_FORMAT(p1.end_time,"%Y-%m-%d") as end_time,
            DATE_FORMAT(p1.teardown,"%Y-%m-%d") teardown,
            p4.name as building,
            p1.bid')
            ->join('company p2', 'p2.id = p1.cid', 'left')
            ->join('building p4', 'p4.id = p1.bid', 'left')
            ->where($condition)
            ->order('id', 'desc')
            ->limit($start, $pagesize)
            ->select();
        $typeList = Db::name('service_type')->field('id,name,status')->select();
        $companyList = Db::name('company')->field('id,name')->select();
        $buildingList = Db::name('building')->field('id,name')->select();

        $count = Db::name('brj')->alias('p1')->where($condition)->count();
        $res = array(
            'list' => $list,
            'typeList' => $typeList,
            'companyList' => $companyList,
            'buildingList' => $buildingList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['cid']) || empty($data['cid']) || !isset($data['brj']) || empty($data['brj']) || !isset($data['address']) || empty($data['address']) || !isset($data['type']) || empty($data['type']) || !isset($data['speed']) || empty($data['speed']) || !isset($data['s_price']) || empty($data['s_price']) || !isset($data['contact']) || empty($data['contact']) || !isset($data['phone']) || empty($data['phone']) || !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (empty($data['end_time'])) {
            $data['end_time'] = NUll;
        }

        if (empty($data['start_time'])) {
            $data['start_time'] = NUll;
        }

        if (empty($data['teardown'])) {
            $data['teardown'] = NUll;
        }

        if (!preg_match('/^1[3456789]{1}\d{9}$/', $data['phone'])) {
            $this->ajaxReturnMsg(202, '手机号格式错误', '');
        }

        // 判断用户是否存在
        if (Db::name('brj')->where('brj', $data['brj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        if ($data['brj'] != $this->getCode()) {
            $this->ajaxReturnMsg(204, '非法的业务编号', '');
        }

        Db::startTrans();
        try {
            $param = array(
                'cid' => $data['cid'],
//                'brj' => $data['brj'],
                'type' => $data['type'],
                'address' => $data['address'],
                'speed' => $data['speed'],
                's_price' => $data['s_price'],
                'contact' => $data['contact'],
                'phone' => $data['phone'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => $data['status'],
                'teardown' => $data['teardown'],
                'bid' => $data['bid'],
                'create_time' => date("Y-m-d H:i:s")
            );
            Db::name('brj')->insertGetId($param);

            Db::commit();
            $this->ajaxReturnMsg(200, 'success', '');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturnMsg(204, '添加失败', '');

        }


    }

    /*
     * 获取brj号码
     */
    public function add()
    {
        $brj = $this->getCode();

        $this->ajaxReturnMsg(200, 'success', $brj);
    }

    private  function getCode()
    {
        $code = Db::name('brj')->field('brj')->limit(1)->order('id','desc')->find();

        if(empty($code['brj'])){
            $brj = "brj" . "10001";
        }else{
            $brj = "brj" . (intval( substr($code['brj'],3)) + 1);
        }
        return $brj;
    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['id']) || empty($data['id']) || !isset($data['cid']) || empty($data['cid']) || !isset($data['brj']) || empty($data['brj']) || !isset($data['address']) || empty($data['address']) || !isset($data['type']) || empty($data['type']) || !isset($data['speed']) || empty($data['speed']) || !isset($data['s_price']) || empty($data['s_price']) || !isset($data['contact']) || empty($data['contact']) || !isset($data['phone']) || empty($data['phone']) || !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (empty($data['end_time'])) {
            $data['end_time'] = NUll;
        }

        if (empty($data['start_time'])) {
            $data['start_time'] = NUll;
        }

        if (empty($data['teardown'])) {
            $data['teardown'] = NUll;
        }

        if (!preg_match('/^1[3456789]{1}\d{9}$/', $data['phone'])) {
            $this->ajaxReturnMsg(202, '手机号格式错误', '');
        }

        // 判断用户是否存在
        if (Db::name('brj')->where('id', '<>', $data['id'])->where('brj', $data['brj'])->count()) {
            $this->ajaxReturnMsg(203, '业务编号已经存在', '');
        }

        Db::startTrans();
        try {
            $param = array(
                'cid' => $data['cid'],
                'brj' => $data['brj'],
                'type' => $data['type'],
                'address' => $data['address'],
                'speed' => $data['speed'],
                's_price' => $data['s_price'],
                'contact' => $data['contact'],
                'phone' => $data['phone'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => $data['status'],
                'teardown' => $data['teardown'],
                'bid' => $data['bid'],
            );
            Db::name('brj')->where('id', $data['id'])->update($param);

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
        $falg = Db::name('brj')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $param = array(
            'deleted' => 1,
            'delete_time' => date("Y-m-d H:i:s")
        );
        $flag = Db::name('brj')->where('id', $data['id'])->update($param);
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}