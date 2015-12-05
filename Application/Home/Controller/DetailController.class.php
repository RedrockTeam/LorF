<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/25
 * Time: 上午10:49
 */

namespace Home\Controller;


class DetailController extends CommonController{

    /**
     * 详情信息页
     */
    public function index(){

        $proId = I('id');
        $share = $this->shareApi();

        $find = M('product_list')->where(array(
            'pro_id' => $proId
        ))->select();

        $list = getList($find);
//dd($list['0']);
        $this->assign('detail', $list['0']);
        $this->assign('share', $share);
//        dd(getList($find));
        $this->display();
    }
}