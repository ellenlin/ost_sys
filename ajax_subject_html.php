<?php

/**
 * ajax题目HTML数据
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
 * 引入题目操作模块
 * @since 1
 */
require_once(DIR_LIB . DS . 'oa-post.php');
require_once(DIR_LIB . DS . 'plug-substrutf8.php');
require_once(DIR_LIB . DS . 'plug-subject.php');

/**
 * 当前用户ID
 * @since 1
 */
$post_user = $oauser->get_session_login();

/**
 * 获取题目HTML
 * @since 1
 */
if (isset($_GET['bank']) == true) {
    if ($_GET['bank'] > 0) {
        $bank_id = $_GET['bank'];
        $plugsubject = new plugsubject($bank_id, $db, $ip_arr['id'], $post_user);
        require(DIR_LIB . DS . 'plug-feedback.php');
        plugfeedbackheaderjson();
        $html = $plugsubject->html_get();
        if ($html) {
            plugfeedbackjson($html, 'success');
        } else {
            plugfeedbackjson('', 'error');
        }
    }
}

/**
 * 交卷处理
 * @since 1
 */
if (isset($_POST['subject']) == true) {
    if ($_POST['subject'] != '') {
        
    }
}
?>
