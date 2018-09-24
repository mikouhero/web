<?php

namespace app\admin\controller;

use think\Controller;

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


}