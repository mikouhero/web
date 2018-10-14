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



class Company extends Base
{
    public function index()
    {
        return view("admin@company/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.id'] = ['>','0'];
        if(isset($data['code']) && !empty($data['code'])){
            $condition['p1.code'] = [ '=', $data['code']];
        }

        $list = Db::name('company')
            ->alias('p1')
            ->field('p1.id,p1.code,p1.name,p1.address,p1.contact,p1.phone,p1.license_icon')
            ->where($condition)
            ->limit($start, $pagesize)
            ->select();
        foreach ($list as $k => $v){
            $account = Db::name('cid_account')->where('cid',$v['id'])->select();
            $list[$k]['account'] = $account;
         }
        $count = Db::name('company')->alias('p1')->where($condition)->count();
        $res = array(
            'companyList' => $list,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if(isset($data['id']) && !empty($data['id'])){
            if (! Db::name('company')->where('id', $data['id'])->count()) {
                $this->ajaxReturnMsg(202, '网络错误', '');
            }
            if (!isset($data['account']) || empty($data['account'])) {
                $this->ajaxReturnMsg(201, '参数错误', '');
            }
            $param = array(
                'cid' => $data['id'],
                'account' => $data['account']
            );
            Db::name('cid_account')->insert($param);
            $this->ajaxReturnMsg(200, 'success', '');
        }
        if (!isset($data['code']) || empty($data['code']) || !isset($data['name']) || empty($data['name']) || !isset($data['address']) || empty($data['address'])  || !isset($data['contact']) || empty($data['contact'])|| !isset($data['phone']) || empty($data['phone'])  ||  !isset($data['license_icon']) || empty($data['license_icon'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('company')->where('code', $data['code'])->count()) {
            $this->ajaxReturnMsg(202, '编号已存在', '');
        }

        $img = base64_image_content($data['license_icon'], 'upload/license');
        if (!$img) {
            $this->ajaxReturnMsg(203, '上传失败', '');
        }
        $img= config('base_url') .'/'. $img;

        //加密
        $param = array(
            'code' =>$data['code'],
            'name' =>$data['name'],
            'address' =>$data['address'],
            'contact' =>$data['contact'],
            'phone' =>$data['phone'],
            'license_icon' =>$img,

        );

        $id = Db::name('company')->insert($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function update(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);
        if (!isset($data['code']) || empty($data['code']) || !isset($data['name']) || empty($data['name']) || !isset($data['address']) || empty($data['address'])  || !isset($data['contact']) || empty($data['contact'])|| !isset($data['phone']) || empty($data['phone']) ) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('company')->where('id', '<>', $data['id'])->where('code', $data['code'])->count()) {
            $this->ajaxReturnMsg(202, '编号已经存在', '');
        }
        $param = array(
            'code' => $data['code'],
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'contact' => $data['contact'],
        );
        if ($data['newpic']) {
            $img = base64_image_content($data['newpic'], 'upload/license');
            if (!$img) {
                $this->ajaxReturnMsg(202, '上传失败', '');
            }
            $param['license_icon'] = config('base_url') .'/'. $img;
            // 删除原来的图片
            $len = strlen(Request::instance()->domain());
            $path = substr($data['license_icon'], $len + 1); // 图片路径
            if (file_exists($path)) {
                unlink($path);
            }
        }
        Db::name('company')->where('id', $data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('company')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $flag = Db::name('company')->where('id',$data['id'])->delete();
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }
}