<!DOCTYPE html>
<html>
<head>
	<title><?=$title;?></title>
	<meta charset="utf-8">
	<style type="text/css">
		a{
			text-decoration: none;
			color:#467;
			font-size: 15px;
		}

      #btn{
      	width: 60px;
      	height: 30px;
      	font-size:15px;
      	text-align: center;
      }
      div a{
      	border:1px solid #cdcdcd;
      	margin: 10px;
      	margin-top:20px;
      	width:50px;
      	display: inline-block;
      	border-radius: 5px 5px 5px 5px; 
      	height:30px;
      	font-size:15px;
      	line-height: 30px;
      	background:pink;

      }
      div{
      	text-align: center;
      	margin-top:20px;
      }
	</style>
	<script type="text/javascript" src="./public/js/jquery-1.8.3.js"></script>
	<script type="text/javascript">
			$(function(){
				//添加操作
				$('#btn').click(function(){
				//	alert(1);
					$.ajax({
						'type':'post',
						'url':'http://localhost/demo/ajaxjquery/add.php',
						'async':true,
						'dataType':'json',
						'data':{
							"username":$('#username').val(),
							"password":$('#password').val()
						},
						'success':function(data){
						
							var str="<tr align='center'>"+
							"<td>"+data.id+"</td>"+
							"<td class='name'>"+data.username+"</td>"+
							"<td>"+data.password+"</td>"+
							"<td>"+data.time+"</td><td><a id="+data.id+"  href='javascript:;' class='del' >删除</a></td></tr>";
							$('#table #tbody').html('');
							$('#table #tbody').append(str);
						}
					});
					//设置input value空
					$('#username').attr('value','');
					$('#password').attr('value','');

				});

				
				//双击修改
				$('.name').live('dblclick',function(){

					var name=window.prompt('输入用户名');
					var id=$(this).parent().children("td").eq(0).text();
				
					$.ajax({
						'type':'post',
						'url':'http://localhost/demo/ajaxjquery/update.php',
						'async':true,
						'dataType':'json',
						'data':{
							"id":id,
							"username":name
						},
						'success':function(data){
							if(data.status){
								alert('修改成功');

								//重新加载本页数据
							}
						}
					});
					
					$(this).parent().children("td").eq(1).text(name);

				});
				//分页的页数
				var page=1;
				$('.page a').click(function(){
					//按钮内容
					var info=$(this).text();	
					if(info=='首页')
					{
						page=1;
						getPageInfo(page);
					}else if(info=='上一页'){

						page--;
						if(page<=1){
							page=1;
						}
					
						getPageInfo(page);

					}else if(info=='下一页'){

						page++;
						var k=<?=$pages;?>;
						if(page>=k){
							page=k;
						}
						getPageInfo(page);
						
					}else if(info=='尾页'){

						page=<?=$pages;?>;
						
						getPageInfo(page);
							
					}
				});


				$('.del').live('click',function(){
				
					$.ajax({
						'type':'post',
						'url':'http://localhost/demo/ajaxjquery/del.php',
						'async':true,
						'dataType':'json',
						'data':{
							"id":$(this).attr('id')
						},
						'success':function(data){
							if(data.status){
								alert('删除成功');
								getPageInfo(page);
							}
						}
					});

					$(this).parent().parent().remove();


				});

			});


		//分页数据信息
		function getPageInfo(page){
			$.ajax({
				'type':'post',
				'url':'http://localhost/demo/ajaxjquery/fenye.php',
				'async':true,
				'dataType':'json',
				'data':{
					"page":page
				},
				'success':function(datas){
					//alert(1);
					var str='';
					for(var i=0;i<datas.length ;i++){
					  str+="<tr align='center'>"+
						"<td>"+datas[i].id+"</td>"+
						"<td class='name'>"+datas[i].username+"</td>"+
						"<td>"+datas[i].password+"</td>"+
						"<td>"+datas[i].time+"</td><td><a id="+datas[i].id+"  href='javascript:;' class='del' >删除</a></td></tr>";
					}
					$('#table #tbody').html('');
					$('#table #tbody').append(str);
					
				}
			});
		}
	</script>
</head>
<body>
<center>
	用户：<input type="text" name="username" id="username"><br/><br/>
	密码：<input type="password" name="password" id="password"><br/>
	<br/>
	<input type="button" value="添加" id="btn">
</center>
	
	<h1 align="center">用户列表</h1>
	<table  width="800" border="1" cellpadding="0"  id="table" cellspacing="0" align="center" id="usertable">
		<thead>
			<tr align="center">
				<th>编号</th>
				<th>用户名</th>
				<th>密码</th>
				<th>时间</th>
				<th>操作</th>
			</tr>	
		</thead>	
		<tbody id="tbody" >
			<?php if (!empty($userInfo)): ?>
			<?php foreach ($userInfo as $key => $val): ?>
			<tr align="center">
				<td><?=$val['id'];?></td>
				<td class="name" ><?=$val['username'];?></td>
				<td><?=$val['password'];?></td>
				<td><?=$val['time'];?></td>
				<td><a href="javascript:;" id="<?=$val['id'];?>" class="del">删除</a></td>
			</tr>
		    <?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
	<div class="page"><a href="javascript:;">首页</a><a href="javascript:;">上一页</a><a href="javascript:;">下一页</a><a href="javascript:;">尾页</a></div>
</body>
</html>