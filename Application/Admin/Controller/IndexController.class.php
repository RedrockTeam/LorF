<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {

    public function index(){
        $this->assign('user_email', session('user_email'));
        $this->assign('user_name', session('user_name'));
        $this->assign('login_time', session('user_login_time'));
        $this->assign('login_ip', session('user_login_ip'));
        $this->display();
    }

    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect('Admin/Login/index');
    }
}