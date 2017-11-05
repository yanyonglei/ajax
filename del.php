<?php

include './tpl/tpl_func.php';
include './common/home.php';

$id=$_POST['id'];

$res=del($conn,'user','id='.$id);


if($res){

	echo json_encode(['status'=>1]);
}else{
	echo json_encode(['status'=>0]);
}


