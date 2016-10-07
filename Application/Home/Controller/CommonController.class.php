<?php
namespace Home\Controller;
use Think\Controller\RestController;
/*
*后台公用控制器
*/
class CommonController extends RestController {

	public function _initialize(){

		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->redirect('Home/Login/index');
		}

		$notAuth = in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODEL'))) || in_array(ACTION_NAME, explode(',', C('NOT_AUTH_ACTION')));

		if (C('USER_AUTH_ON') && !$notAuth) {
			$Rbac = new \Org\Util\Rbac();
			$Rbac->AccessDecision() || $this->error('没有权限');
		}
	}
}
?>