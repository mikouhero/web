<?php
namespace app\admin\controller;


use think\Controller;
use think\Db;

class Crontab extends Controller
{

    public function deleteTmp()
    {
          $dir = '../runtime/temp/';
          $handle = opendir($dir);
          while ($filename=readdir($handle)){
              if($filename == '.' || $filename=='..'){
                  continue;
              }
              $path = $dir.'/'.$filename;
              if(is_file($path)){
                  //删除1小时之前的temp
                if(((time()-filectime($path)) > 3600 )){
                    unlink($path);
                }
              }
          }
    }

    public function deleteCache()
    {
        $dir = '../runtime/cache/';
        $this->del($dir);
    }

    public function deleteErrorLog()
    {
        $dir = '../runtime/log/';
        $handle = opendir($dir);
        while ($filename=readdir($handle)){
            if($filename == '.' || $filename=='..'){
                continue;
            }
            $path = $dir.'/'.$filename;
            if(is_file($path)){
                if(((time()-filectime($path)) > 3600*30 )){
                    unlink($path);
                }
            }
            if(is_dir($path)){
                $name = basename($path);
                $pass =  date("Ym",strtotime('-2 months'));
                if($pass >= $name){
                   // 删除 name 下的log
                    $this->del($dir.$name.'/');
                    rmdir($dir.$name.'/');
                }
            }
        }

    }

    private function del($dir)
    {
        $handle = opendir($dir);
        while ($filename=readdir($handle)){
            if($filename == '.' || $filename=='..'){
                continue;
            }
            $path = $dir.$filename;
            if(is_file($path)){
                //删除1小时之前的temp
                if(((time()-filectime($path)) > 3600 )){
                    unlink($path);
                }
            }
            if(is_dir($path)){
                if(count(scandir($path))==2){ // 目录为空,=2是因为. 和 ..存在
                    rmdir($path);             // 删除空目录
                }else{
                    $this->del($path.'/');
                }

            }
        }
        closedir($handle);

    }
    // crj 过其
    public function updateCrj(){
        $timeout = Db::name('crj')
            ->field('p1.id')
            ->alias('p1')
            ->where('status',1)
            ->where('end_time', '<', date("Y-m-d H:i:s"))
            ->select();
        if($timeout){
            foreach ($timeout as $k=>$v){
               $tmp[] = $v['id'];
            }

            Db::name('crj')->whereIn('id',$tmp)->update(['status'=>2]);
        }
        echo "ok";

    }

}