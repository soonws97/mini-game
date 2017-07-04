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
	<span id="user"><?php echo Yii::$app->session['userName'];?></span>
	
	<?php 	$today = date('Y-m-d');
			$userName =Yii::$app->session['userName'];
			$uid = User::find()->where('userName = :name' ,[':name' => $userName])->one()->userID;
			$gamecheck = User::find()->where('userID = :id' , [':id' => $uid ])->one()->gameCheck;
			$userdata = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one();
			$gamecount = User::find()->where('userID = :id' , [':id' => $uid ])->one();
			
			?>
			
	<a href="<?php echo yii\helpers\Url::to(['site/logout'])?>" class="log">登出</a>
			
	
	
		<h1>您是否是我们要找的那个有缘人呢~~~</h1>
	</div>

	<div id="middle">


		<div id="left" >
			<h2 id="min" >
				<?php 
					if($gamecheck <=0 ){
						
						
					}
					else{
						
							$time = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
							$ansNow = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->ans;
							$val = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->playingNow;
							$getToken = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->token;
							$ansTime =  date("Y-m-d", strtotime($time));
							$nowMin = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->min_value;
						
						if($val == $ansNow)
						{
							
							
							
							
						}
						else{
							
							echo $nowMin;
						}
						
						

					}
				?>
			</h2>
		</div>

		<div id="mid">
			<h2 id="to" >
				<?php 
					
					if($gamecheck <=0 ){
						
					}
					
					else{
						
						if($val == $ansNow)
						{
							
							echo $ansNow;
							
							
						}
						else{
							echo "到";
						}
					}
				?>
			</h2>
		</div>

		<div id="right" >
			<h2 id="max" >
			<?php 
					if($gamecheck <=0 ){
						
					}
					else{
						if($val == $ansNow)
						{
							
							
						}
						else{
							$nowMax = GameRecord::find()->where('userID = :id and playTime =:t' , [':id' => $uid , ':t'=> $time ])->one()->max_value;
							echo $nowMax;
						}
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
		<input type="text" maxlength="2" name="value" id="value" placeholder="1 - 99" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  autofocus />
		<input type="button" class="btn" value="提交" id="btnSubmit" onclick="verifyorder(document.getElementById('value').value)" />

		</p>

		<hr>
	</div>
		<div class="rule">
			<span id="gametitle" ><b> ！游戏规则 ！ </b></span>
			<br><br>
			<span>
			在1 - 99 之间猜个数字。每位用户只能有<mark><b>5</b></mark>次猜的机会，您只需要猜中和我们所想的一样号码，就能领取我们派送的奖励。
			<br>
			第一次直接中奖将获得奖励10元 / 第二次中奖将获得奖励5元 / 第三,四,五次中奖将获得奖励2元。
			</span>
		</div>
			<div class="chg">
			<br>
			<?php 
			if($gamecheck ==5){
				
				if($getToken == 0)
						{
							echo "恭喜你，您就是我们要找的幸运儿！";
						}
						else{
							echo "您今天的次数已达成。请明天再来。";
							}
			}
			elseif($gamecheck <5)
			{
			?>
				您还有 <?php echo 5-$gamecheck ?> 次机会哟。
			<?php 
			}
			?>
			

			</div>

			<h5 id="copyright">Copyright © 政隆 & Weisheng </h5>
</div>

</body>

</html>




