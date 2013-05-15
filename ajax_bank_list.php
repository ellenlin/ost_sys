<?php

/**
 * ajax获取题库列表
 * @author fotomxq <fotomxq.me>
 * @version 1
 * @package ost_sys
 */
/**
 * 引入用户登录检测模块(包含全局引用)
 * @since 1
 */
require('logged.php');

/**
 * 初始化变量
 * @since 1
 */
$page = 1;
$max = 10;
$sort = 0;
$desc = true;
$bank_type = '';

/**
 * 获取题库列表
 */
$bank_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, '');
?>
