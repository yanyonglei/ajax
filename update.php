<?php

include './tpl/tpl_func.php';
include './common/home.php';

$id=$_POST['id'];
$username=$_POST['username'];

//update($link,$table ,$data,$where)

$res=update($conn,'user',['username'=>$username],'id='.$id);

if($res){

	echo json_encode(['status'=>1]);
}



