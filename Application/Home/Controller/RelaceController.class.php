<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/6
 * Time: 下午4:05
 */
namespace Home\Controller;
use Think\Controller;

class RelaceController extends CommonController{

    /**
     * 发布信息页面
     */
    public function index() {
        $this->assign('contact', $this->_getContact());
        $this->assign('kinds', $this->_getKinds(0));
        $this->display();
    }

    /**
     * 获取种类信息
     */
    private function _getKinds($kindId) {
        if ($kindId == 0) 
            $kinds = M('product_kinds')->select();
        else {
            $kindArr = M('product_kinds')->where(array('kind_id' => $kindId))->find();
            $kinds = $kindArr['kind_name'];
        }

        return $kinds;
    }

    /**
     * 获取联系方式
     */
    private function _getContact() {

        $info = M('user_info')->where('user_id = 1')->find();
        $contact = array(
            'phone' => $info['phone_num'],
            'qq' => $info['tencent_num'],
            'people'=> $info['stu_name']
        );

        return $contact;
    }

    /**
     * 处理发布的信息
     */
    public function relaceHandle() {

        // 获取post过来的数组
        $post = I('post.');

        // 判断不能为空值
        foreach($post as $key => $value)
            if($value == null)
                $this->error('数据填写不能为空! ');

        $data = array(
            'pro_name'=> $this->_getKinds($post['kind']),
            'pro_description'=> $post['remark'],
            'L_or_F_time'=> $this->_timeStyle(I('post.time')),
            'L_or_F_place'=> $post['place'],
            'connect_name'=> $post['contact_people'],
            'connect_wx'=> $post['contact_qq'],
            'wx_avatar' => 'http://hongyan.cqupt.edu.cn/lostandfound/img/avatar.jpg',
            'connect_phone' => $post['contact_phone'],
            'created_at'=> date('Y-m-d H:i:s', time()),
            'lost_or_found'=> $post['status'],
            'check_state'=> 1,
            'status'=> 0
        );

        // 保存添加
        $this->_saveData($data);
    }

    /**
     * 保存发布的信息
     * @param $data 发布的信息数组
     */

    private function _saveData($data) {
        if (is_null($data))
            $this->error('数据错误');

        $re_id = M('product_list')->add($data);

        if ($re_id != null)
            $this->success('发布成功!');
        else 
            $this->error('发布失败!');
    }

    /**
     * 把前端传过来的时间字符转转接在转成int返回
     * @param $str 前端发来的时间字符串
     * @return int 返回的int类型时间戳
     */

    private function _timeStyle($str) {
        $year = substr($str, 6,5);
        $monthAndDay = substr($str, 0,5);

        return date('Y-m-d', strtotime($year.'/'.$monthAndDay));
    }
}