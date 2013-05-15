<?php

/**
 * 获取题库类型列表模块
 * <p>需要：oa-configs类</p>
 * @author fotomxq <fotomxq.me>
 * @version 1
 * @package ost_sys
 * @param oaconfigs $oaconfig 配置操作句柄
 * @return array 获取题库类型数组
 */
function pluggetbank(&$oaconfig) {
    $config_bank_type = $oaconfig->load('OST_BANK_TYPE');
    $bank_type_arr = explode(',', $config_bank_type);
    return $bank_type_arr;
}

?>
