<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/6
 * Time: 下午4:01
 */
namespace Admin\Controller;
use Think\Controller;

class CheckController extends CommonController{

    /**
     * 信息审核页面
     */
    public function index() {

        $searchName = I('search');
        if ($searchName !== "") {   //按nickname查询

            $where['check_state'] = 0;
            $where['status'] = 0;
            $where['pro_name'] = array('like','%'.$searchName.'%');

            $count = M('product_list')
                ->where($where)
                ->count();

            $Page = new \Think\Page($count,10);// 实例化分页类
            $show = $Page->show();// 分页显示输出
            $list = M('product_list')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->where($where)
                ->select();
        }else{

            $count = M('product_list')
                ->where(array(
                    'check_state' => 0,
                    'status' => 0,
                ))
                ->count();

            $Page = new \Think\Page($count,10);// 实例化分页类
            $show = $Page->show();// 分页显示输出
            $list = D('product_list')
                ->where(array(
                    'check_state' => 0,
                    'status' => 0,
                ))
//                ->order(array('stu_num' => 'desc'))
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        }

        $this->assign('list', getList($list));// 赋值数据集
        $this->assign('show',$show);// 赋值分页输出

        $this->display();
    }

    /**
     * 审核信息
     */
    public function checkHandle() {
        $data = array(
            'pro_id' => I('get.uid'),
            'check_state' => 1
        );
        $r = M('product_list')->save($data);
        if($r == 1){
            $this->redirect('Check/index', array('cate_id' => 2), 0, '审核成功');
        }else{
            $this->error('审核失败');
        }
    }
}