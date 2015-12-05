<?php
namespace Mobile\Controller;
use Think\Controller\RestController;

/**
 * 移动端接口控制器
 * Class IndexController
 * @package Mobile\Controller
 */
class IndexController extends RestController {

    private $pageNum = 4;

    public function _initialize(){

    }

    /**
     * 首页接口
     */
    public function index() {
        $stu = I('post.stuNum');

        $first = $this->_isFirstVisit($stu);

        $lost = M('product_list')->where(array(
            'lost_or_found' => 0,
            'status' => 0,
            'check_state' => 1
        ))
            ->order('pro_id desc')
            ->limit($this->pageNum)
            ->select();

        $found = M('product_list')->where(array(
            'lost_or_found' => 1,
            'status' => 0,
            'check_state' => 1
        ))
            ->order('pro_id desc')
            ->limit($this->pageNum)
            ->select();

        $this->ajaxReturn(array(
           'isFirst' => $first,
           'lost' => getList($lost),
            'found' => getList($found)
        ));
    }

    /**
     * 搜索接口
     */
    public function search() {
        //获取搜索的物品名称
        $searchName = I('post.searchName');
        $kind = I('kind');  // 寻物0, 招领1
        $where['pro_description'] = array('like','%'.$searchName.'%');
        $where['status'] = 0;
        $result = M('product_list')->where(array(
            'pro_description' => array('like','%'.$searchName.'%'),
            'status' => 0,
            'lost_or_found' => $kind
        ))
            ->order('pro_id desc')
            ->limit($this->pageNum)
            ->select();

        $this->ajaxReturn(array(
            'data' => getList($result)
        ));
    }

    /**
     * 分页接口
     */
    public function page() {
        $page = I('post.page');
        $LorF = I('post.LorF');

        $from = $this->_getFrom($page);

        $result = M('product_list')->where(array(
                'lost_or_found' => $LorF,
                'status' => 0,
                'check_state' => 1
        ))
            ->order('pro_id desc')
            ->limit($from, $this->pageNum)
            ->select();

        $this->ajaxReturn(array(
            'data' => getList($result)
        ));

    }

    /**
     * 个人信息完善接口
     */
    public function perfectInfo() {
        $stu = I('post.stuNum');
        $realName = I('realName');
        $phone = I('post.phone');
        $qq = I('post.qq');

        $user = M('user_info')->where(array(
            'stu_num' => $stu
        ))->find();

        if($user){  // 如果用户已存在
            $data = array(
                'user_id' => $user['user_id'],
                'stu_num' => $stu,
                'stu_name' => $realName,
                'phone_num' => $phone,
                'tencent_num' => $qq,
//                'headImgUrl' => $headUrl['headImageUrl']
            );

            $re = M('user_info')->save($data);
            if($re == 1){
                $result = 200;
                $info = 'success';
            }else{
                $result = -400;
                $info = 'error';
            }
        }else{  // 如果不存在就添加用户
            $addData = array(
                'stu_num' => $stu,
                'stu_name' => $realName,
                'phone_num' => $phone,
                'tencent_num' => $qq,
//                'headImgUrl' => $headUrl['headImageUrl']
            );

            $uid = M('user_info')->add($addData);

            if(!is_null($uid)){ //传回来的是userid
                $result = 200;
                $info = 'success';
            }else{
                $result = -400;
                $info = 'error';
            }
        }

        $this->ajaxReturn(array(
            'status' => $result,
            'info' => $info
        ));
    }

    /**
     *
     */
    public function infoList() {

    }

    /**
     * 详情信息页面
     */
    public function detailInfo() {
        $proId = I('post.id');  //某一条消息的id

        $find = M('product_list')->where(array(
            'pro_id' => $proId
        ))->select();

        $list = getList($find);

        if($find){
            $resault['status'] = 200;
            $resault['info'] = 'success';
        }else{
            $resault['status'] = -400;
            $resault['info'] = 'error';
        }

        $resault['data'] = $list['0'];

        $this->ajaxReturn($resault);
    }

    /**
     * 获取个人资料
     */
    public function userInfo() {
        $stu = I('post.stuNum');

        $re = M('user_info')->where(array(
            'stu_num' => $stu
        ))
            ->find();
        if($re){
            $result['status'] = 200;
            $result['info'] = 'success';
        }else{
            $result['status'] = -400;
            $result['info'] = 'error';
        }

        $result['data'] = $re;

        $this->ajaxReturn($result);
    }

    /**
     * 发布信息接口
     */
    public function releaseInfo() {
        $stu = I('post.stuNum');
        $kind = I('post.kind');
        $type = I('post.type');
        $date = I('post.date');
        $place = I('post.place');
        $content = I('post.content');


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
     * 判断是否是第一次访问, 如果不是第一次把用户id存到session
     * @param  [type]  $stuNum 学号
     * @return boolean         是第一次访问返回1, 不是返回0
     */
    private function _isFirstVisit($stuNum){

        $where['stu_num'] = $stuNum;
        $user = M('user_info')->where($where)->find();

        if (is_null($user)) {
            $result = 1;
        }else{
            $result = 0;
            session('relace_user_id', $user['user_id']);
        }

        return $result;
    }

    /**
     * 根据页数判断从第几条开始
     * @param $page
     * @return mixed
     */
    private function _getFrom($page) {
        $from = ($this->pageNum) * ($page-1);
        return $from;
    }
}