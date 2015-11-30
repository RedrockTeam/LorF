<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/21
 * Time: 下午4:43
 */

namespace Home\Controller;

class UserInfoController extends CommonController{

    private function _getSelfId(){

//        return session('relace_user_id');
        return 11;
    }

    public function index(){

        $this->assign('selfDone', $this->_getSelfDone());
        $this->assign('selfRelace', $this->_getSelfRelace());
        $this->assign('selfInfo', $this->_getSelfInfo());
//dd($this->_getSelfRelace());
        $this->display();
    }

    /**
     * 点击加载更多
     */
    public function nextPage(){

        $selfId = $this->_getSelfId();
        $where['pro_user_id'] = $selfId;
        //获取要加载的分页信息
        $from = I('from');
        $num  = I('num');
        $DorR = I('DorR'); //标识符 未解决1 已解决2
//        dd($DorR);
        if($DorR == 1){
            $where['status'] = 0;
        }if($DorR == 2){
            $where['status'] = 1;
        }
//dd($where);
        $list = M('product_list')
            ->where($where)
            ->order('pro_id desc')
            ->limit($from, $num)
            ->select();

        $count = M('product_list')
            ->where($where)
            ->order('pro_id desc')
            ->select();
//dd($list);
        if($list){
            $status = 1;
        }else{
            $status = 0;
        }
//dd($where);
        // 如果count小于4 返回空
        if($count < 4){
            $this->ajaxReturn(array(
                'status' => $status,
                'nextPage' => getList()
            ));
        }else{
            $this->ajaxReturn(array(
                'status' => $status,
                'nextPage' => getList($list)
            ));
        }

    }

    public function handleDone(){
        $data = array(
            'pro_id' => I('id'),
            'status' => 1
        );
        $r = M('product_list')->save($data);
        if($r == 1){
            $status = 1;
        }else{
            $status = 0;
        }

        $this->ajaxReturn(array(
            'status' => $status
        ));

    }

    /**
     * 得到个人已解决的条目
     */
    private function _getSelfDone(){

        // 获取到本人在user_info表中的id
        $selfId = $this->_getSelfId();

        $list = M('product_list')
//            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
            ->where(array(
                    'pro_user_id' => $selfId,
                    'status' => 1
                ))
            ->order('pro_id desc')
            ->limit(4)
            ->select();

        return getList($list);
    }

    /**
     * 得到个人已发布的条目
     */
    private function _getSelfRelace(){
        // 获取到本人在user_info表中的id
        $selfId = $this->_getSelfId();

        $list = M('product_list')
//            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id, status')
            ->where(array(
                'pro_user_id' => $selfId
            ))
            ->order('pro_id desc')
            ->limit(4)
            ->select();

        return getList($list);
    }

    /**
     * 得到user_info表里面的个人信息
     */
    private function _getSelfInfo(){

        // 获取到本人在user_info表中的id
        $selfId = $this->_getSelfId();

        $info = M('user_info')
                    ->where(array(
                        'user_id' => $selfId
                    ))
                    ->find();

        return $info;
    }
}