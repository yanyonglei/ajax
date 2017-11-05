<?php

	include './tpl/tpl_func.php';
	include './common/home.php';

	$title='用户列表';

	$total=total($conn,'user','*','id');
	$offset=5;
	$pages=ceil($total/$offset);
	
	$userInfo=select($conn,'user','*','id limit 0,5');

	display('userlist.html',compact('title','userInfo','pages'));

