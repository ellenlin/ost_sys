<?php

/**
 * 验证码
 * @author suhuiling
 * @version 1
 * @package ost_sys
 */
sleep(1);
require('glob.php');
require('includes/plug-vcode.php');
require('includes/plug-headernocache.php');
plugheadernocache();
plugvcode(4, 20, 150, 35);
?>
