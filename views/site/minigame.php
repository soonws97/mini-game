<!DOCTYPE html>
<?php 
use app\models\GameRecord;
use app\models\GameResult;
use app\models\User;
?>
<head>
<meta charset="UTF-8">
	<title>终极密码</title>
</head>

<body>
	<div id="top">
	<?php echo Yii::$app->session['userName']?>
	<?php 	$today = date('Y-m-d');
			$userName =Yii::$app->session['userName'];
			$uid = User::find()->where('userName = :name' ,[':name' => $userName])->one()->userID;
			$gamecheck = User::find()->where('userID = :id' , [':id' => $uid ])->one()->gameCheck;
			$userdata = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one();
			$gamecount = User::find()->where('userID = :id' , [':id' => $uid ])->one();
			$checkPlaying = GameRecord::find()->where('userID = :id and token =:tk' , [':id' => $uid , ':tk'=> 1 ])->one();
			
			
			?>
	
		<h1>您是否是我们要找的那个有缘人呢~~~</h1>
	</div>

	<div id="middle">


		<div id="left" >
			<h2 id="min" >
				<?php 
					if(empty($checkPlaying)){
						
						
					}
					else{
						
						$time = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
						$ansNow = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->ans;
						$val = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->playingNow;
						$getToken = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->token;
						$ansTime =  date("Y-m-d", strtotime($time));
						$nowMin = GameRecord::find()->where('userID = :id and token =:tk' , [':id' => $uid , ':tk'=> 1 ])->one()->min_value;
						echo $nowMin;
					}
				?>
			</h2>
		</div>

		<div id="mid">
			<h2 id="to" >
				<?php 
					
					if(empty($checkPlaying)){
						
					}
					elseif($getToken == 0){	
					var_dump($ansTime);var_dump($today);exit;
						if($ansTime == $today && $ansNow == $val)
							echo "恭喜！";
						} 
						elseif($ansTime == $today && $ansNow!=$val && $getToken==0){
							echo "用完了哦";
						}
					else{
						echo "到";
						var_dump($getToken);
						
					}
				?>
			</h2>
		</div>

		<div id="right" >
			<h2 id="max" >
			<?php 
					if(empty($checkPlaying)){
						
					}
					else{
						$nowMax = GameRecord::find()->where('userID = :id and token =:tk' , [':id' => $uid , ':tk'=> 1 ])->one()->max_value;
						echo $nowMax;
					}
				?>
			</h2>
		</div>


	</div>


<div id="bottom">
	<div id="game">

		<p>
			请输入您心中所想的号码。
		<br />
		<input type="text" maxlength="2" name="value" id="value" placeholder="1 - 99" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  />
		<input type="button" class="btn" value="提交" id="btnSubmit" onclick="verifyorder(document.getElementById('value').value)" />

		</p>

		<hr>
	</div>
		<div class="rule">
			<span id="gametitle" ><b> ！游戏规则 ！ </b></span>
			<br><br>
			<span>
			在1 - 99 之间猜个数字。每位用户只能有<mark><b>5</b></mark>次猜的机会，您只需要猜中和我们所想的一样号码，就能领取我们派送的奖励。
			</span>
		</div>
			<div class="chg">
				<h4 id="time"></h4>

			</div>

			<h5 id="copyright">Copyright © 政隆 </h5>
</div>

</body>


</html>