<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/6
 * Time: 下午1:34
 */

/**
 * 浏览器友好变量输出
 * @param $var 要输出的变量
 * @param bool|true $echo
 * @param null $label
 * @param bool|false $strict
 * @return null|void
 */
function dd($var, $echo=true, $label=null, $strict=false) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        die($output);
        return null;
    }else
        return die($output);
}

/**
 * 把种类信息添加到数组里
 * @param $list 原数组
 * @return array 添加了种类信息的数组
 */
function getList($list) {

    $url = 'http://hongyan.cqupt.edu.cn'.U('Home/Detail/index', '', '');
    $reList = array();

    foreach($list as $k => $v){
        $condition['kind_id'] = $v['pro_kind_id'];
        $kindName = M('product_kinds')->where($condition)->find();
        $condition2['user_id'] = $v['pro_user_id'];
        $user = M('user_info')->where($condition2)->find();
        $v['kind_name'] = $kindName['kind_name'];
        $v['relace_user_name'] = $user['stu_name'];

        if($user['headImgUrl'] == 1){
            $v['relace_head_url'] = 'http://hongyan.cqupt.edu.cn/LorF/src/images/qingxie.jpg';
        }else{
            $v['relace_head_url'] = $user['headImgUrl'];
        }

        $v['phone'] = $user['phone_num'];
        $v['qq'] = $user['tencent_num'];
        $v['detail_url'] = $url.'/id/'.$v['pro_id'];
        array_push($reList, $v);
    }

    return $reList;
}