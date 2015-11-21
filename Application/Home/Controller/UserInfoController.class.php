<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/21
 * Time: 下午4:43
 */

namespace Home\Controller;

class UserInfoController extends CommonController{

    public function index(){

        $this->assign('selfDone', $this->_getSelfDone());
        $this->assign('selfRelace', $this->_getSelfRelace());
        $this->assign('selfInfo', $this->_getSelfInfo());
//dd($this->_getSelfRelace());
        $this->display();
    }

    /**
     * 得到个人已解决的条目
     */
    private function _getSelfDone(){

        // 获取到本人在user_info表中的id
        $selfId = session('relace_user_id');
        // 测试用
        $selfId = 7;
        $list = M('product_list')
            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
            ->where(array(
                    'pro_user_id' => $selfId,
                    'status' => 1
                ))
            ->order('pro_id desc')
            ->select();

        return getList($list);
    }

    /**
     * 得到个人已发布的条目
     */
    private function _getSelfRelace(){
        // 获取到本人在user_info表中的id
        $selfId = session('relace_user_id');
        // 测试用
        $selfId = 7;
        $list = M('product_list')
            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id, status')
            ->where(array(
                'pro_user_id' => $selfId
            ))
            ->order('pro_id desc')
            ->select();

        return getList($list);
    }

    /**
     * 得到user_info表里面的个人信息
     */
    private function _getSelfInfo(){

        // 获取到本人在user_info表中的id
        $selfId = session('relace_user_id');
        // 测试用
        $selfId = 7;
        $info = M('user_info')
                    ->where(array(
                        'user_id' => $selfId
                    ))
                    ->find();

        return $info;
    }


}