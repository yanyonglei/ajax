<?php

/*
	功能:链接mysql 
	@localhost: 主机名
	@user:用户名
	@password 密码
	@charset 字符集
	@dbName 数据库名
 */

function connection ($host,$user,$pass,$charset,$name){
	

	$link=mysqli_connect($host,$user,$pass);
	
	if(!$link){
		exit('数据库连接失败.....');
	}
	mysqli_set_charset($link,'utf8');

	mysqli_select_db($link,$name);

	return $link;
}
/*
	功能:数据库的插入数据
	@link 链接标志
	@data 数组
	@table 表名

 */
function insert($link,$table,$data){


	$key=array_keys($data);

	$fields=implode(',',$key);

	$value=array_values($data);

	$values=implode(',',parseValue($value));

	$sql="insert into $table($fields) values($values)";
	//var_dump($sql);
	$res=mysqli_query($link,$sql);
	if ($res) {
		return mysqli_insert_id($link);
	}else{
		return false;
	}
}

/*
功能:数组的解析
@data 
return  array 
 */
function parseValue($data){

	if(is_string($data))
	{
		$data='\''.$data.'\'';
	}else if(is_array($data)){
		$data=array_map('parseValue',$data);
	}else if(is_null($data)){
		$data='';
	}
	return $data;

}

/*
	功能:数据库数据的更新
	@link
	@data  array
	@table string 表名
	@where string 条件

 */
function update($link,$table ,$data,$where){
	$set=join(',',parseSet($data));

	$sql="update $table set $set where $where";
  	//
  	//var_dump($sql);
	
	$res=mysqli_query($link,$sql);

	return $res;

}

/*
功能:数组的解析  key=Val
@data 
return  array 
 */
function parseSet($data){

	foreach ($data as $key => $value) {
		$val=parseValue($value);

		if(is_scalar($val)){
			$set[]=$key.'='.$val;
		}
	}
	return $set;
}

/*
	功能:数据库数据的删除数据
	@link
	@table string 表名
	@where string 条件

 */

function del($link,$table,$where){

	$sql="delete from $table where $where";
	//var_dump($sql);
	$res=mysqli_query($link,$sql);


	if($res && mysqli_affected_rows($link)){
		return $res;
	}else{
		return false;
	}

}
/*
	功能:数据库数据的查询数据
	@link
	@table string 表名
	@where string 条件
	@fields string 字段区域

 */
function select ($link,$table,$fields,$where=''){
	if (empty($where)) {
		$where='';
	}else{
		$where="where $where";
	}
	$sql=" select $fields from $table $where";

	//echo $sql;

	$res=mysqli_query($link,$sql);
	
	if ($res && mysqli_affected_rows($link)) {

		while($rows=mysqli_fetch_assoc($res)){
			$data[]=$rows;
		}
		//var_dump($data);
	}else{
		return false;
	}	
	return $data;
}
/*
	功能:数据库数据最大值
	@link
	@fields string 字段区域
	@table string 表名

 */
function big($link,$table,$fields){
	$sql="select max($fields) as max from $table";
	//var_dump($sql);
	$res=mysqli_query($link,$sql);


	if($res && mysqli_affected_rows($link)){
		return mysqli_fetch_assoc($res)['max'];
	}else{
		return false;
	}
}

/*
	功能:数据库数据最小值
	@link
	@fields string 字段区域
	@table string 表名

 */
function small($link,$table,$fields){

	$sql="select min($fields) as min from $table";
	$res=mysqli_query($link,$sql);


	if($res && mysqli_affected_rows($link)){

		return mysqli_fetch_assoc($res)['min'];
	}else{
		return false;
	}
}
/*
	功能:数据库数据某个字段总数
	@link
	@fields string 字段区域
	@table 表名

*/

function sum($link,$table,$fields){
	
	$sql="select sum($fields) as  sum  from $table";
	$res=mysqli_query($link,$sql);


	if($res && mysqli_affected_rows($link)){

		return mysqli_fetch_assoc($res)['sum'];
	}else{
		return false;
	}
}


/*
	功能:数据库数据某个字段和
	@link
	@fields string 字段区域
	@table 表名

*/
function total($link,$table,$fields){
	
	$sql="select count($fields) as total from $table";

	$res=mysqli_query($link,$sql);
	//var_dump($sql);

	if($res && mysqli_affected_rows($link)){

		return mysqli_fetch_assoc($res)['total'];
	}else{
		return false;
	}
}


function totalMore($link,$table,$fields,$where){
	
	$sql="select count($fields) as total from $table where $where";

	$res=mysqli_query($link,$sql);
	//var_dump($sql);

	if($res && mysqli_affected_rows($link)){

		return mysqli_fetch_assoc($res)['total'];
	}else{
		return false;
	}
}

/*
	功能:数据库数据某个字段平均值
	@link
	@fields string 字段区域
	@table 表名

*/
function pj($link,$table ,$fields){
	$sql="select avg($fields) as pj from $table";

	$res=mysqli_query($link,$sql);
	if ($res && mysqli_affected_rows($link)) {
		return mysqli_fetch_assoc($res)['pj'];
	}else{
		return false;
	}
}
//封装批量删除的sql函数
//
function delMore($link,$table ,$where){
	$sql="delete from $table where $where";

	//var_dump($sql);
	$res=mysqli_query($link,$sql);
	if ($res && mysqli_affected_rows($link)){
		return $res;
	}else{
		return false;
	}
}