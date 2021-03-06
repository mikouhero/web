<?php
/**
 * Created by PhpStorm.
 * User: Mikou.hu
 * Date: 2018/9/11
 */

namespace app\admin\controller;

use gmars\rbac\Rbac;
use think\Controller;
use think\Db;
use think\Request;

class Role extends Base
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
        return view('admin@role/index');
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $list = Db::name('role')->field('id,name,description,status')->limit($start, $pagesize)->select();
        $count = Db::name('role')->count();
        $newList = $this->getRolePermission($list);
        $permissionList = $this->getAllPermission();
        $res = array(
            'list' => $newList,
            'permissionList' => $permissionList,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function insert(Request $request)
    {
        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['name']) || empty($data['name']) || !isset($data['description']) || empty($data['description']) || !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }
        // 判断用户是否存在
        if (Db::name('role')->where('name', $data['name'])->count()) {
            $this->ajaxReturnMsg(202, '角色已存在', '');
        }
        $param = $data;
        $param['create_time'] = date("Y-m-d H:i:s");

        $rbac = new Rbac();
        $flag = $rbac->createRole($param);
        if (!$flag) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }

        $this->ajaxReturnMsg(200, 'success', $flag);

    }

    public function edit(Request $request)
    {

        $input = $request->post();
        $data = json_decode($input['msg'], true);

        if (!isset($data['name']) || empty($data['name']) || !isset($data['description']) || empty($data['description']) || !isset($data['status'])) {
            $this->ajaxReturnMsg(201, '参数错误', '');
        }

        // 判断用户是否存在
        if (Db::name('role')->where('id', '<>', $data['id'])->where('name', $data['name'])->count()) {
            $this->ajaxReturnMsg(202, '用户名已存在', '');
        }
       $param =array(
           'name' => $data['name'],
           'status' => $data['status'],
           'description'=>$data['description']
       );
        Db::name('role')->where('id', $data['id'])->update($param);
        $this->ajaxReturnMsg(200, 'success', '');

    }

    public function delete(Request $request)
    {
        $data = $request->post();
        if (!isset($data['id']) || empty($data['id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('role')->where('id', $data['id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }
        $rbac = new Rbac();
        $flag = $rbac->delRole($data['id']);
        if (!$flag) $this->ajaxReturnMsg(202, '网络错误', '');
        $this->ajaxReturnMsg(200, 'success', '');
    }

    public function assignPermission(Request $request)
    {
        $data = $request->post();
        if (!isset($data['role_id']) || empty($data['role_id']) || !isset($data['permission_id']) || empty($data['permission_id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('role_permission')->where('role_id', $data['role_id'])->where('permission_id', $data['permission_id'])->count();
        if ($falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }

        $rbac = new Rbac();
        $rbac->assignRolePermission($data['role_id'], [$data['permission_id']]);
        $this->ajaxReturnMsg(200, 'success', '');
    }

    public function deletePermission(Request $request)
    {
        $data = $request->post();
        if (!isset($data['role_id']) || empty($data['role_id']) || !isset($data['permission_id']) || empty($data['permission_id'])) {
            $this->ajaxReturnMsg(201, '缺少参数', '');
        }
        $falg = Db::name('role_permission')->where('role_id', $data['role_id'])->where('permission_id', $data['permission_id'])->count();
        if (!$falg) {
            $this->ajaxReturnMsg(201, '网络错误', '');
        }

        Db::name('role_permission')->where('role_id', $data['role_id'])->where('permission_id', $data['permission_id'])->delete();
        $this->ajaxReturnMsg(200, 'success', '');

    }

    private function getRolePermission($list)
    {
        foreach ($list as $k => $v) {
            $permission = Db::name('permission')->alias('p1')->field('p1.id,p1.name')
                ->join('role_permission p2', 'p1.id = p2.permission_id', 'left')
                ->join('role p3', 'p2.role_id = p3.id', 'left')
                ->where('p3.id', $v['id'])
                ->where('p1.status', 1)
                ->select();
            $list[$k]['permission'] = $permission;
        }
        return $list;
    }

    private function getAllPermission()
    {
        $data = Db::name('permission')->field('id,name')->where('status', 1)->select();
        return $data;
    }


}