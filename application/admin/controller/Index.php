<?php

namespace app\admin\controller;

use think\Db;

class Index extends Base
{
    public function index()
    {
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
        return view('index');
    }

    public function getList()
    {
        //1 获取所有留言数
      $msg = $this->detail('note');
      $member = $this->detail('member');
      $crj = $this->detail('crj');
      $brj = $this->detail('brj');
      $visit = $this->detail('visit');

        $memberlog  = $this->detail('login_log',$arr=['source'=>['=',1]]);

      $this->ajaxReturnMsg(200,'success',array(
          'msg' => $msg,
          'member'=> $member,
          'crj' => $crj,
          'brj'=> $brj,
          'memberlog'=>$memberlog,
          'visit'=>$visit,

      ));
    }


    protected  function detail($table,$arr = array())
    {
        $data = cache($table);
        if(!$data){
            $where['id'] = ['>', '0'];
            if($arr){
                foreach ($arr as $k => $v){
                    $where[$k] = $v;
                }
            }
            $count= Db::name($table)->where($where)->count();

            $where['create_time'] = ['>=',date("Y-m-d")];
            $today = Db::name($table)->where($where)->count();
            if($count-$today == 0 && ($today !=0)){
                $speed = "100%";
            }elseif ($count-$today == 0 && ($today ==0)){
                $speed = "0%";

            } else{
                $speed = intval($today / ($count)*100) .'%';
            }
            $data = array(
                'count' => $count,
                'today' => $today,
                'speed' => $speed
            );
            cache($table,$data,['expire'=>3600]);
        }
        return $data;

    }

}