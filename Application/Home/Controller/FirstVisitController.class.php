<?php
namespace Home\Controller;


/**
 * 第一次访问页面控制器
 */
class FirstVisitController extends CommonController {

    /**
     * 第一次访问首页
     * 返回用户信息包括 昵称, 头像地址, 真实姓名, 学号
     */
    public function index(){
        $userInfo = $this->_getUserInfo(session('openid'));
        $userInfo['realName'] = $this->_getRealName(session('openid'));
        $userInfo['stuNum'] = $this->_getStuNum(session('openid'));
//dd($userInfo);

        $this->assign('userInfo', $userInfo);
        $this->display();
    }

    /**
     * 处理更新的信息
     */
    public function handleInfo(){
        // openid ouRCyjsp3eo3FJil24fV625V_6no
        $where['stu_num'] = I('stu_num');
        $headUrl = $this->_getUserInfo(session('openid'));
        $user = M('user_info')->where($where)->find();
        $result = 0;
        // 如果用户已存在
        if($user){
            $data = array(
                'user_id' => $user['user_id'],
                'stu_num' => I('stu_num'),
                'stu_name' => I('real_name'),
                'phone_num' => I('phone'),
                'tencent_num' => I('qq'),
                'headImgUrl' => $headUrl['headImageUrl']
            );

            $result = M('user_info')->save($data);
        }else{
            $addData = array(
                'stu_num' => I('stu_num'),
                'stu_name' => I('real_name'),
                'phone_num' => I('phone'),
                'tencent_num' => I('qq'),
                'headImgUrl' => $headUrl['headImageUrl']
            );

            $r = M('user_info')->add($addData);

            if(!is_null($r)){
                session('relace_user_id', $r);
                $result = 1;
            }
        }
        $this->ajaxReturn(array(
            'status'=> $result
        ));
    }
}