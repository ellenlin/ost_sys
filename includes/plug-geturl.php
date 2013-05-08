<?php

/**
 * 获取当前页面的URL
 * @author suhuiling
 * @version 1
 * @package pluggeturl
 * @return string URL
 */
function pluggeturl() {
    return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
}

?>
