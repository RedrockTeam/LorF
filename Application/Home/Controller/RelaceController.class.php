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
        $info = M('user_info')->where(array('user_id' => session('relace_user_id')))->find();

        $this->assign('kinds', $kinds);
        $this->assign('info', $info);
        $this->display();
    }

    public function handleInfo(){
        $post = I('get.');
//dd($post);
//        $mark = 0;
        $info = '电话: '.$post['phone'].' QQ: '.$post['qq'];

        $kindname = M('product_kinds')->where(array('kind_id' => I('kind')))->find();
        $connectpeople = M('user_info')->where(array('user_id' => session('relace_user_id')))->find();

        // 判断不能为空值
//        foreach($post as $key => $value){
//            if($value == null){
//                if($key == 'phone') {
//                    $mark = 1;
//                    $info = 'QQ: '.$post['qq'];
//                }else if($mark == 0 && $key == 'qq') {
//                    $info = '电话: '.$post['phone'];
//                }else {
//                    $this->ajaxReturn(array(
//                        'status' => 0
//                    ));
//                }
//            }
//        }

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
                    'check_state'=> 1,
                    'status'=> 0
                ));
//dd($sta);
        $this->ajaxReturn(array(
            'status'=>$sta
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
     * 把前端传过来的时间字符转转接在转成int返回
     * @param $str 前端发来的时间字符串
     * @return int 返回的int类型时间戳
     */

    private function _timeStyle($str) {

        return strtotime($str);
    }
}