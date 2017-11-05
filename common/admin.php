<?php


include '../helper/mysql.php';
include '../config/database.php';




//判断是否安装
/*if (!file_exists('install.lock')) {
	header('location:install/index.php');
}
*/


//连接apple_bbs数据库
$conn=connection(DB_HOST,DB_USER,DB_PWD,DB_CHARSET,DB_NAME1);
if (!$conn) {
	exit('数据库连接失败');
}


//待处理：IP是否禁用，网站是否关闭








