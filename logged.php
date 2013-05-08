<?php

/**
 * 已登录检测
 * <p>如果发现尚未登录，则直接中断页面</p>
 * @author suhuiling
 * @version 1
 * @package ost_sys
 */
/**
 * 引入全局
 * @since 1
 */
require('glob.php');

/**
 * 引入用户类
 * @since 1
 */
require(DIR_LIB . DS . 'oa-user.php');

/**
 * 进行登录检测
 * @since 2
 */
//读取用户超时配置
$config_user_timeout = (int) $oaconfig->load('USER_TIMEOUT');
$oauser = new oauser($db);
$logged_admin = false;
if ($oauser->status($ip_arr['id'], $config_user_timeout) == true) {
    $logged_user = $oauser->view_user($oauser->get_session_login());
    if ($logged_user) {
        $logged_group = $oauser->view_group($logged_user['user_group']);
        if ($logged_group) {
            if ($logged_group['group_power'] == 'admin') {
                $logged_admin = true;
            }
        }
    }
} else {
    //如果尚未登录处理
    plugerror('logged');
}
unset($config_user_timeout);
?>
