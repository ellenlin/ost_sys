<?php

/**
 * ajax获取题库列表
 * @author fotomxq <fotomxq.me>
 * @version 2
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
$bank_type = isset($_GET['type']) ? $_GET['type'] : 0;

/**
 * 获取题库列表
 * @since 2
 */
$oapost->set_where_name_on(true);
$bank_list = $oapost->view_list(null, null, null, 'public', 'bank', $page, $max, $sort, $desc, null, $bank_type);

/**
 * 引用反馈头处理模块
 * @since 1
 */
require(DIR_LIB . DS . 'plug-feedback.php');

/**
 * 反馈JSON
 * @since 1
 */
plugfeedbackheaderjson();
plugfeedbackjson($bank_list);
?>
