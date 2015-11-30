<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 失物招领微信访问首页
 */
class IndexController extends CommonController {

    private $oauth2Url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx81a4a4b77ec98ff4&redirect_uri=http%3A%2F%2Fhongyan.cqupt.edu.cn%2FLorF%2Findex.php%2FHome%2FIndex%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
    /**
     * 微信端失物招领首页
     */
    public function index(){

        // 获取到openid并且存到session
        $code = I('get.code');

        if(!$code){
            return $this->redirect($this->oauth2Url);
        }

        $openId = $this->_getOpenId($code);
//        $openId = 'ouRCyjsp3eo3FJil24fV625V_6no';
        session('openid', $openId);

        //判断是否绑定学号, 是否关注重邮小帮手
        // $isBind = $this->_checkBind($openId);
        // $care = $this->_checkCareXBS($openId);

        // 获取学号和微信昵称以及头像
         $stuNum = $this->_getStuNum(session('openid'));
        // $userInfo = $this->_getUserInfo(session('openid'));

        // 判断是否第一次访问, 传值为学号, 如果是第一次访问, 跳转到第一次访问页面
        $first = $this->_isFirstVisit($stuNum);
        if($first){
            $this->redirect('Home/FirstVisit/index');
        }

        // 获取失物招领信息, 限制为4条
        $lost = M('product_list')
//            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
                                 ->where(array('lost_or_found' => 0, 'status' => 0))->order('pro_id desc')->limit(4)->select();
        $found = M('product_list')
//            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
                                  ->where(array('lost_or_found' => 1, 'status' => 0))->order('pro_id desc')->limit(4)->select();
//dd(getList($found));
        $this->assign('lost', getList($lost));
        $this->assign('found', getList($found));

        $this->display();
    }

    /**
     * 首页搜索方法
     */
    public function search(){

        //获取搜索的物品名称
        $searchName = I('searchName');
        $kind = I('kind');  // 寻物0, 招领1
//        dd($kind);
        $where['pro_description'] = array('like','%'.$searchName.'%');
        $where['status'] = 0;
        $where['lost_or_found'] = $kind;
        $result = M('product_list')->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
                                 ->where($where)
                                 ->order('pro_id desc')->limit(10)->select();

        $this->assign('result', getList($result));
//dd(getList($result));
        $this->display();
    }

    /**
     * 分页获取信息
     */
    public function nextPage(){

        //获取要去的的分页信息
        $from = I('from');
        $num  = I('num');
        $LorF = I('Lorf');

        $result = M('product_list')
//            ->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
                                ->where(array(
                                    'lost_or_found' => $LorF,
                                    'status' => 0
                                ))
                                ->order('pro_id desc')->limit($from, $num)->select();

        $count = M('product_list')->where(array(
            'lost_or_found' => $LorF,
            'status' => 0
        ))->count();

        if($result){
            $status = 1;
        }else{
            $status = 0;
        }

        // 如果count小于4 返回空
        if($count < 4){
            $this->ajaxReturn(array(
                'status' => $status,
                'nextPage' => getList()
            ));
        }

        $this->ajaxReturn(array(
            'status' => $status,
            'nextPage' => getList($result)
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
}