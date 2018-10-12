<?php
namespace app\admin\controller;


use think\Controller;

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
                $this->del($path.'/');
            }
        }
        closedir($handle);

    }

}