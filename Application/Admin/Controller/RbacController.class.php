<?php
namespace Admin\Controller;
use Think\Controller;
/**
* 
*/
class RbacController extends CommonController {

	//用户列表
	public function index(){
		//取出用户信息
		$User = D('UserRelation');

		$searchNum = I('search');
		if ($searchNum !== "") {   //按nickname查询

			$count = $User->field('password', true)->relation(true)
						  ->where(array('stu_num' => $searchNum))
						  ->count();

			$Page = new \Think\Page($count,10);// 实例化分页类

			$list = $User->field('password', true)->relation(true)
                         ->order(array('stu_num' => 'desc'))
						 ->limit($Page->firstRow.','.$Page->listRows)
						 ->where(array('stu_num' => $searchNum))
						 ->select();
		}else{

			$count = $User->field('password', true)->relation(true)
						  ->count();

			$Page = new \Think\Page($count,10);// 实例化分页类

			$list = $User->field('password', true)->relation(true)
                         ->order(array('stu_num' => 'desc'))
						 ->limit($Page->firstRow.','.$Page->listRows)
						 ->select();
		}
    	$show = $Page->show();// 分页显示输出
    	$this->assign('list',$list);// 赋值数据集
    	$this->assign('show',$show);// 赋值分页输出
        $this->assign('user_email', session(user_email));
        $this->assign('user_name', session(user_name));
        $this->display();
	}

	//角色列表
	public function role(){
		$role = M('role')->select();
		$this->assign('role',$role)->display();
	}

	//部门列表
	public function dept(){
		$dept = M('department')->select();
		$this->assign('dept',$dept)->display();
	}

	//节点列表
	public function node(){
		$field = array('id', 'name', 'title', 'pid');
		$node = M('node')->field($field)->order('sort')->select();
		$node = node_merge($node);
		$this->assign('node',$node)->display();
	}

	//添加用户界面
	public function addUser(){
		$role = M('role')->select();
		$this->assign('role', $role);

		$this->display();
	}

	//添加用户表单管理
	public function addUserHandle(){

        $rules = array(
            array('stu_num','10','请输入正确的学号!', 1, 'length'),
            array('stu_num','verify_stu','学号已存在!', 1, 'function'),
            array('stu_idcard','6','请输入身份证后六位!',1,'length'),
            array('email','email','请输入正确的邮箱!',1,),
            array('nickname','require','请填写昵称!',2),
            array('realname','require','请填写真实姓名!',1),
            array('password','require','请填写密码!',1),
        );
        $User = M("user_member"); // 实例化User对象
        if (!$User->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($User->getError());
        }else{
            // 验证通过 可以进行其他数据操作
//            dd(I('post.'));
            $user = array(
                'stu_num' => I('stu_num'),
                'stu_idcard' => I('stu_idcard'),
                'password' => I('password', '', 'md5'),
                'email' => I('email'),
                'nickname' => I('nickname'),
                'real_name' => I('realname'),
                'last_login_time' => time(),
                'last_login_ip' => get_client_ip(),
                'status' => '1'
            );

            //所属权限
            $role = array();
            if($uid = M('user_member')->add($user)){
                    $role[] = array(
                        'role_id' => I('role'),
                        'user_id' => $uid
                    );
                //添加权限
                M('user_role')->addAll($role);

                $this->success('添加用户成功', U('Admin/Rbac/index'));
            }else{
                $this->error('添加用户失败');
            }
        }
	}

	//删除用户表单管理
	public function deleteUser(){
		$r1 = M('user_member')->where(array('id' => I('uid')))->delete();
		$r2 = M('user_role')->where(array('user_id' =>I('uid')))->delete();

		if ($r1 && $r2 ) {
			$this->success('删除用户成功');
		}else{
			$this->error('删除用户失败');
		}
	}

    //修改用户信息
    public function setRole(){
        $uid = I('uid');

        $user = D('UserRelation')->field('password', true)->relation(true)->find($uid);
        $this->assign('user', $user);
        $role = M('role')->select();
        $this->assign('role', $role);
        $this->display();
    }

    //修改用户信息处理
    public function setRoleHandle(){
        $rules = array(
            array('stu_num','10','请输入正确的学号!', 1, 'length'),
            array('stu_idcard','6','请输入身份证后六位!',1,'length'),
            array('email','email','请输入正确的邮箱!',1,),
            array('nickname','require','请填写昵称!',2),
            array('realname','require','请填写真实姓名!',1),
            array('password','require','请填写密码!',1),
        );
        $User = M("user_member"); // 实例化User对象
        if (!$User->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->error($User->getError());
        }else{
            // 验证通过 可以进行其他数据操作
//            dd(I('post.'));
            $user = array(
                'stu_num' => I('stu_num'),
                'stu_idcard' => I('stu_idcard'),
                'password' => I('password', '', 'md5'),
                'email' => I('email'),
                'nickname' => I('nickname'),
                'real_name' => I('realname'),
                'last_login_time' => time(),
                'last_login_ip' => get_client_ip(),
                'status' => '1'
            );

            //所属权限
            $u_id = I('uid');
            if($uid = M('user_member')->where("id=%d",$u_id)->save($user)){
                $role = array(
                    'role_id' => I('role'),
                    'user_id' => $u_id
                );
                //添加权限
                M('user_role')->where("user_id=%d",$u_id)->save($role);

                $this->success('修改信息成功', U('Admin/Rbac/index'));
            }else{
                $this->error('修改信息失败');
            }
        }
    }

	//添加角色
	public function addRole(){
		$this->display();
	}

	//添加角色表单处理
	public function addRoleHandle(){
		if (!I('name') || !I('remark')) {
			$this->error('名称或描述不能为空');
		}
		$result = M('role')->add(I('post.'));

		if($result){
			$this->success('添加角色成功', U('Admin/Rbac/role'));
		}else{
			$this->error('添加失败');
		}
	}

	//删除角色表单管理
	public function deleteRole(){
		$r1 = M('role')->where(array('id' => I('rid')))->delete();
		$r2 = M('user_role')->where(array('role_id' =>I('rid')))->delete();
		$r3 = M('access')->where(array('role_id' =>I('rid')))->delete();
		if ($r1 || $r2 || $r3) {
			$this->success('删除角色成功');
		}else{
			$this->error('删除角色失败');
		}
	}

	//添加节点
	public function addNode(){
		$pid = I('get.pid', 0, 'intval');
		$level = I('level', 1, 'intval');
		$this->assign('pid', $pid);
		$this->assign('level', $level);
		switch ($this->level) {
			case 1:
				$this->type = '应用';
				break;	
			case 2:
				$this->type = '控制器';
				break;
			case 3:
				$this->type = '方法';
				break;
		}

		$this->display();
	}

	//添加节点表单处理
	public function addNodeHandle(){

		$result = M('node')->add(I('post.'));

		if($result){
			$this->success('添加应用成功', U('Admin/Rbac/node'));
		}else{
			$this->error('添加失败');
		}
	}

	//删除节点表单处理
	public function deleteNode(){
		$r1 = M('node')->where(array('id' => I('id')))->delete();
		$r2 = M('access')->where(array('node_id' =>I('id')))->delete();
		if ($r1 || $r2) {
			$this->success('删除节点成功');
		}else{
			$this->error('删除节点失败');
		}
	}

	//给角色配置权限
	public function access(){
		$rid = I('rid', 0, 'intval');

		$field = array('id', 'name', 'title', 'pid');
		$node = M('node')->order('sort')->field($field)->select();
		
		//原有权限
		$access = M('access')->where(array('role_id' => $rid))->getField('node_id', true);

		$node = node_merge($node,$access);

		$this->assign('node', $node);
		$this->assign('rid', $rid);
		$this->display();
	}

	//修改权限
	public function setAccess(){
		$rid = I('rid', 0, 'intval');
		$db = M('access');

		//清空原权限
		$db->where(array('role_id' => $rid))->delete();

		//组合新权限
		$data= array();
		foreach (I('access') as $v) {
			$tmp = explode('_', $v);
			$data[] = array(
				'role_id' => $rid,
				'node_id' => $tmp[0],
				'level' => $tmp[1]
				);
		}

		//插入新权限
		$result = $db->addAll($data);
		if($result){
			$this->success('修改权限成功', U('Admin/Rbac/role'));
		}else{
			$this->error('修改失败');
		}
	}
}
?>
