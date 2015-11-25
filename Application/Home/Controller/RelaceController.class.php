<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/25
 * Time: 上午11:33
 */

namespace Home\Controller;


class RelaceController extends CommonController{

    /**
     * 发布首页
     */
    public function index(){
        $kinds = M('product_kinds')->select();

        $this->assign('kinds', $kinds);
        $this->display();
    }

    public function handleInfo(){

    }
}