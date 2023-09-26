<!DOCTYPE html> 
<html> 
	<head> 
		<meta charset="utf-8"> 
		<title>タスク</title> 
		<link rel="stylesheet" href="style.css"> 
	</head> 
	<body>  
		<form method="post" action="check.php">
			<table> 
			<div align="center"><h3>タスク作成</h3></div>
				<tr> 
					<th>タスク名</th> 
					　　<td>　<input type="text" name="uname" size="30"></td> 
				</tr> 
				<tr> 
					<th>重要度</th>
					<td>　<input type="radio" name="gender" value="高">高
					　　　<input type="radio" name="gender" value="中">中
					　　　<input type="radio" name="gender" value="低">低
				</td> 
				</tr> 
				<tr> 
					<th>日時</th> 
					<td>
						　<input type="date" value="2023-07-07">
					</td>
				</tr> 
				<tr> 
					<th>想定時間</th>
					<td>
						　<input type="number">
						<p>　<button>+</button></p>
						　<input type="number">
						　<p id="total">　合計：</p>
					</td>
				</tr>
				<tr> 
					<th>モード</th> 
					<td>　<input type="radio" name="gender" value="コツコツ">コツコツ
					　　　<input type="radio" name="gender" value="集中">集中
					</td> 
				</tr> 

				<tr> 
					<th>TypeTalk</th> 
					<td>
					　<input type="checkbox" name="dm" value="SNS">完成しなかった場合、自動投稿される
					    　<p id="sns" style="display:none">完成しなかった場合</p>
					<?php
						if(empty(@$_POST["SNS"])){
							$dm="なし";
						}else{
						echo $dm = implode("",$_POST["SNS"]);
						}
					?>
					</td>
					
					<tr>
						<td>
						<div><a href="#" class="btn-square">作成</a></div>
						</td>
					</tr>
					</td>
				</tr> 
			</table> 
		</form> 
	</body> 
</html>

