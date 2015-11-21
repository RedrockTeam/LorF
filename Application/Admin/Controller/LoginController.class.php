<?php
namespace Admin\Controller;
use Think\Controller;
/*
*后台登陆控制器
*/
class LoginController extends Controller {

	public function index(){
        $this->display();
    }

    //登录验证
    public function login(){
    	if(!IS_POST){
    		$this->error('页面不存在');
    	}

		$username = I('post.username');
		$password = I('post.password');
		$user = M('admin_user')->where(array('admin_name' => $username))->find();

        $md5_pwd = md5($password);

        if($user && $user['admin_pwd'] == $md5_pwd){
            $this->_successHandle($user);
        }else{
            $this->error('账号或密码错误', __ROOT__ . '/index.php/Admin/Login/index');
        }
    }

    private function _successHandle($user){
        $data = array(
            'admin_id' => $user['admin_id'],
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(),
        );
        M('admin_user')->save($data);

        session(C('USER_AUTH_KEY'), $user['admin_id']);
        session('user_name', $user['admin_name']);
        session('user_login_time', date('Y-m-d H:i:s', $user['last_login_time']));
        session('user_login_ip', $user['last_login_ip']);

        //超级管理员识别
        if ($user['admin_name'] == C('RBAC_SUPERADMIN')) {
            session(C('ADMIN_AUTH_KEY'), true);
        }
        //读取用户权限
        $Rbac = new \Org\Util\Rbac();
        $Rbac->saveAccessList();

        $this->success('登陆成功', __ROOT__ . '/index.php/Admin/Index/index');
    }
}
