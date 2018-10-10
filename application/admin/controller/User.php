<?php
/**
 * Created by PhpStorm.
 * User: Mikou.hu
 * Date: 2018/9/11
 */

namespace app\admin\controller;

use gmars\rbac\Rbac;
use think\Db;
use think\Request;

class User extends Base
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $css = config('style_path.admin_css_path');
        $js = config('style_path.admin_js_path');
        $img = config('style_path.admin_img_path');
        $picture = config('style_path.admin_picture_path');
        $this->assign(array(
            'css' => $css,
            'js' => $js,
            'img' => $img,
            'picture' => $picture
        ));
    }

    public function index()
    {
        return view('admin@user/index');
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $list = Db::name('user')->field('id,user_name,mobile,name,status')->limit($start, $pagesize)->select();
        $newList = $this->getUserRole($list);
        $roleList = $this->getAllRole();
        $count = Db::name('user')->count();
        $res = array(
            'list' => $newList,
            'roleList' => $roleList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['user_name']) || empty($data['user_name']) || !isset($data['name']) || empty($data['name']) || !isset($data['password']) || empty($data['password']) || !isset($data['mobile']) || empty($data['mobile']) ||  !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (!preg_match('/^1[3456789]{1}\d{9}$/', $data['mobile'])) {
            $this->ajaxReturnMsg(202, '手机号格式错误', '');
        }

        // 判断用户是否存在
        if (Db::name('user')->where('user_name', $data['user_name'])->count()) {
            $this->ajaxReturnMsg(202, '用户名已存在', '');
        }

        // 判断手机号是否存在
        if (Db::name('user')->where('mobile', $data['mobile'])->count()) {
            $this->ajaxReturnMsg(202, '手机号已存在', '');
        }

        //加密
        $param = $data;
        $param['password'] = md5($data['password']);
        $param['create_time'] = date("Y-m-d H:i:s");

        $id = Db::name('user')->insertGetId($param);
        $this->ajaxReturnMsg(200, 'success', $id);

    }

    public function edit(Request $request)
    {

        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['user_name']) || empty($data['user_name']) || !isset($data['name']) || empty($data['name']) || !isset($data['mobile']) || empty($data['mobile']) ||  !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        if (!preg_match('/^1[3456789]{1}\d{9}$/', $data['mobile'])) {
            $this->ajaxReturnMsg(202, '手机号格式错误', '');
        }

        // 判断用户是否存在
        if (Db::name('user')->where('id', '<>', $data['id'])->where('user_name', $data['user_name'])->count()) {
            $this->ajaxReturnMsg(202, '用户名已存在', '');
        }

        // 判断手机号是否存在
        if (Db::name('user')->where('id', '<>', $data['id'])->where('mobile', $data['mobile'])->count()) {
            $this->ajaxReturnMsg(202, '手机号已存在', '');
        }

        $param = array(
            "user_name" => $data['user_name'],
            "name" => $data['name'],
            "mobile" => $data['mobile'],
            "status" => $data['status'],

        );
        Db::name('user')->where('id', $data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }

        if($data['id'] == 1){
            $this->ajaxReturnMsg(201, '不允许删除', '');
        }

        $falg = Db::name('user')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $rbac = new Rbac();
        $flag = $rbac->delUser($data['id']);
        if (!$flag) $this->ajaxReturnMsg(202, '', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }

    public function assignRole(Request $request)
    {
        $data = $request->post();
        if (!isset($data['user_id']) || empty($data['user_id']) || !isset($data['role_id']) || empty($data['role_id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }

        $falg = Db::name('user_role')->where('user_id', $data['user_id'])->where('role_id', $data['role_id'])->count();
        if ($falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }

        $rbac = new Rbac();
        $rbac->assignUserRole($data['user_id'], [$data['role_id']]);
        $this->ajaxReturnMsg(200, 'success', '');
    }

    public function deleteRole(Request $request)
    {
        $data = $request->post();
        if (!isset($data['user_id']) || empty($data['user_id']) || !isset($data['role_id']) || empty($data['role_id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('user_role')->where('user_id', $data['user_id'])->where('role_id', $data['role_id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }

        Db::name('user_role')->where('user_id', $data['user_id'])->where('role_id', $data['role_id'])->delete();
        $this->ajaxReturnMsg(200, 'success', '');

    }

    private function getUserRole($list)
    {
        foreach ($list as $k => $v) {
            $role = Db::name('role')->alias('p1')->field('p1.id,p1.name')
                ->join('user_role p2', 'p1.id = p2.role_id', 'left')
                ->join('user p3', 'p2.user_id = p3.id', 'left')
                ->where('p3.id', $v['id'])
                ->where('p1.status', 1)
                ->select();
            $list[$k]['role'] = $role;
        }
        return $list;
    }

    private function getAllRole()
    {
        $data = Db::name('role')->field('id,name')->where('status', 1)->select();
        return $data;
    }

}