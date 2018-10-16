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


class Note extends Base
{
    public function index()
    {
        return view("admin@note/index");
    }

    public function getList(Request $request)
    {
        $data = $request->post();
        $current_page = $data['current_page'];
        $pagesize = 10;
        $start = ($current_page - 1) * $pagesize;
        $condition['p1.id'] = ['>','0'];
        if(isset($data['name']) && !empty($data['name'])){
            $condition['p1.name'] = [ '=', $data['name']];
        }

        $list = Db::name('note')
            ->alias('p1')
            ->field('p1.id,
                     p1.content,
                     p1.member_id,
                     p1.name,p1.ip,
                     p1.create_time,
                     p1.status,
                     p3.name as company')
            ->join('member p2','p1.member_id = p2.id','left')
            ->join('company p3','p3.id = p2.cid' ,'left')
            ->where($condition)
            ->order('p1.status','asc')
            ->order('p1.id','desc')
            ->limit($start, $pagesize)
            ->select();
        $count = Db::name('note')->alias('p1')->where($condition)->count();
        $res = array(
            'list' => $list,
            'count' => ceil($count / $pagesize)
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public function add(Request $request)
    {
        $data = $request->post();

        if(!isset($data['id']) || empty($data['id']) ){
            $this->ajaxReturnMsg(201, '参数错误 ', '');
        }
        $topic = Db::name('note')->field('content,create_time,name')->where('id',$data['id'])->find();
        $topic['create_time'] = formatDate($topic['create_time']);
        //查看
        if((Db::name('note')->field('status')->where('id',$data['id'])->find())['status'] == 0){
            Db::name('note')->where('id',$data['id'])->update(['status'=>1]);
        }
        $list = Db::name('note_comment')
            ->field('user_name,content,create_time,source')
            ->where('note_id',$data['id'])
            ->order('id','asc')
            ->select();
        foreach ($list as $k => $v){
            $list[$k]['create_time'] = formatDate($v['create_time']);
        }
        $res = array(
            'topic' => $topic,
            'list' => $list,
        );
        $this->ajaxReturnMsg(200, 'success', $res);
    }

    public  function insert(Request $request)
    {
        $data = $request->post();

        if(!isset($data['id']) || empty($data['id']) || !isset($data['msg']) || empty($data['msg']) ){
            $this->ajaxReturnMsg(201, '参数错误 ', '');
        }
        $param = array(
            'note_id'        => $data['id'],
            'content'        => $data['msg'],
            'source'         => 2,
            'ip'             =>$request->ip(),
            'user_name'      =>Session::get('manger_user.user_name'),
            'user_id'        =>Session::get('manger_user.id'),
            'create_time'    => date("Y-m-d H:i:s")
         );
        Db::name('note_comment')->insert($param);
        Db::name('note')->where('id',$data['id'])->update(['status'=>2]);
        $this->ajaxReturnMsg(200, 'success', '');
    }

}