<?php

/**
 * ajax题目HTML数据
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
 * 引入反馈头模块
 * @since 2
 */
require(DIR_LIB . DS . 'plug-feedback.php');

/**
 * 获取题目HTML
 * @since 1
 */
if (isset($_GET['bank']) == true) {
    if ($_GET['bank'] > 0) {
        $bank_id = $_GET['bank'];
        $plugsubject = new plugsubject($bank_id, $db, $ip_arr['id'], $post_user);
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
 * @since 2
 */
if (isset($_POST['subject']) == true) {
    if ($_POST['subject'] != '' && isset($_POST['subject'][0][0]) == true) {
        $subjects = $_POST['subject'];
        //获取该试卷所属题库
        $bank_res = null;
        $subject_res = $oapost->view($subjects[0][0]);
        if ($subject_res && isset($subject_res['post_parent']) == true) {
            if ($subject_res['post_parent'] > 0) {
                $bank_res = $oapost->view($subject_res['post_parent']);
            }
        }
        if ($bank_res) {
            $plugsubject = new plugsubject($bank_res['id'], $db, $ip_arr['id'], $post_user);
            $bank_fraction = $plugsubject->html_put($subjects);
            //获取并输出考试得分
            plugfeedbackheaderjson();
            plugfeedbackjson(array($plugsubject->sum_bank_fraction(), $bank_fraction), 'success');
        }
    }
}
?>
