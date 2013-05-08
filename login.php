<?php

/**
 * 登录处理
 * @author suhuiling
 * @version 1
 * @package ost_sys
 */
/**
 * 引入全局定义
 * @since 1
 */
require('glob.php');

/**
 * 引入用户操作封装
 * @since 1
 */
require(DIR_LIB . DS . 'oa-user.php');

/**
 * 检查变量存在并转移给user类
 * @since 3
 */
if (isset($_POST['user']) == true && isset($_POST['pass']) == true && isset($_POST['vcode']) == true && isset($_POST['s']) == true) {
    $remember = false;
    if (isset($_POST['remeber']) == true) {
        $remember = true;
    }
    $user = new oauser($db);
    if ($_POST['s'] == '1') {
        $login_bool = $user->add_user($_POST['user'], $_POST['pass'], 'user@user.com', $_POST['name'], 2, $_GET['ip_id']);
    } else {
        $login_bool = $user->login($_POST['user'], $_POST['pass'], $ip_arr['id'], $remember);
        if ($login_bool == true) {
            plugtourl('init.php');
        } else {
            plugtourl('error.php?e=login');
        }
    }
}
?>