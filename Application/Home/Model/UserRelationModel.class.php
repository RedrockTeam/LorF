<?php
namespace Admin\Model;
use Think\Model\RelationModel;
/**
 * 用户与角色关联模型
 *
 *	**发送多条SQL语句,视图模型用join只发送一条SQL语句**
 */
class UserRelationModel extends RelationModel{

    //定义主表名称
    Protected $tableName = 'user_member';

    /*
    *	**HAS_ONE 一对一关系**
    *	**HAS_MANY 一对多关系**
    *	**MANY_TO_MANY 多对多关系**
    */
    //定义关联关系
    Protected $_link = array(
        'role' => array(
            'mapping_type' => self::MANY_TO_MANY,
            'foreign_key' => 'user_id',				//主表在中间表中的字段名称
            'relation_foreign_key' => 'role_id',	//副表在中间表中的字段名称
            'relation_table' => 'user_role',		//中间表名称(如果有前缀要记得加)
            'mapping_fields' => 'id, name, remark'
        ),
        'user_weixin' => array(
            'mapping_type'      => self::HAS_ONE,
            'foreign_key' => 'user_id'
        )
    );

}
?>