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

        $share = $this->shareApi();

        $kinds = M('product_kinds')->select();
        $info = $this->_getInfo();

        $this->assign('kinds', $kinds);
        $this->assign('info', $info);
        $this->assign('share', $share);
        $this->display();
    }

    public function handleInfo(){
        $post = I('post.');
//dd($post);
        $info = '电话: '.$post['phone'].' QQ: '.$post['qq'];

        $user = $this->_getInfo();

        if($post['phone'] != null){
            $user['phone_num'] = $post['phone'];
        }

        if($post['qq'] != null){
            $user['tencent_num'] = $post['qq'];
        }

        $user['score'] = $user['score'] + 1;

        M('user_info')->save($user);

        $kindname = M('product_kinds')->where(array('kind_id' => I('kind')))->find();
        $connectpeople = M('user_info')->where(array('user_id' => session('relace_user_id')))->find();

        // 保存添加
        $sta =  $this->_saveData(array(
                    'pro_name'=> $kindname['kind_name'],
                    'pro_description'=> $post['content'],
                    'L_or_F_time'=> $this->_timeStyle(I('date')),
                    'L_or_F_place'=> $post['place'],
                    'connect_info'=> $info,
                    'connect_people'=> $connectpeople['stu_name'],
                    'pro_kind_id'=> $post['kind'],
                    'pro_user_id'=> session('relace_user_id'),
                    'create_time'=> time(),
                    'lost_or_found'=> $post['species'],
                    'check_state'=> 0,
                    'status'=> 0
                ));

        $this->ajaxReturn(array(
            'status' => $sta
        ));
    }

    /**
     * 保存发布信息
     * @param $data 要保存的信息
     * @return int 返回保存信息是否成功
     */

    private function _saveData($data) {
        if(is_null($data)){
            $this->error('数据错误');
        }
        $re_id = M('product_list')->add($data);

        if($re_id != null){
            return 1;
        }else {
            return 0;
        }
    }

    /**
     * 通过session的id获取个人信息
     * @return mixed
     */
    private function _getInfo(){
        $info = M('user_info')->where(array(
            'user_id' => session('relace_user_id')
//            'user_id' => 1
        ))->find();

        return $info;
    }


    /**
     * 把前端传过来的时间字符转转接在转成int返回
     * @param $str 前端发来的时间字符串
     * @return int 返回的int类型时间戳
     */

    private function _timeStyle($str) {

        return strtotime($str);
    }
}