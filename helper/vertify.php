<?php
	include 'yzm.php';
	//开启session 回话
	session_start();
	//调用yzm的verfiy()函数 返回字符串
	$yzm=verfiy();
	$_SESSION['yzm']=$yzm;



