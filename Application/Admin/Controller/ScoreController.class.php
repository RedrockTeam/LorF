<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/17
 * Time: 18:50
 */
namespace Admin\Controller;
use Think\Controller;

class ScoreController extends CommonController {

    /**
     * 积分列表页面
     */
    public function index() {
        $searchName = I('search');
        if ($searchName !== "") {   //按学号查询

            $where['stu_num'] = $searchName;

            $count = M('user_info')
                ->where($where)
                ->count();

            $Page = new \Think\Page($count,10);// 实例化分页类
            $show = $Page->show();// 分页显示输出
            $list = M('user_info')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->where($where)
                ->order('score desc')
                ->select();
        }else{

            $count = M('user_info')
                ->count();

            $Page = new \Think\Page($count,10);// 实例化分页类
            $show = $Page->show();// 分页显示输出
            $list = D('user_info')
                ->order('score desc')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        }

        $this->assign('list',$list);// 赋值数据集
        $this->assign('show',$show);// 赋值分页输出
        $this->display();
    }
}