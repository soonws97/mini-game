<!DOCTYPE html>
<?php 
use app\models\GameRecord;
use app\models\GameResult;
use app\models\User;
?>
<head>
<meta charset="UTF-8">
	<title>终极密码</title>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
	<div id="top">
	<span id="user"><?php echo Yii::$app->session['userName'];?></span>
	
		<?php 
			if($gamecheck <=0 ){}
			else{$today = date('Y-m-d');
			$userName =Yii::$app->session['userName'];
			$user = User::find()->where('userName = :name' ,[':name' => $userName])->one();
			$uid = $user->userID;
			$date = GameRecord::find()->where('userID = :id and playDate =:t' , [':id' => $uid , ':t'=> $today ])->one()->playDate;
			$gamecheck = $user->gameCheck;
			}
			if($gamecheck <=0 ){}
					else{
							$gamedata = GameRecord::find()->where('userID = :id and playDate =:t' , [':id' => $uid , ':t'=> $today ])->one();
							
							$ansNow = $gamedata->ans;
							$val = $gamedata->playingNow;
							$getToken = $gamedata->token;
							$nowMin = $gamedata->min_value;
					}
			?>
			
		<a href="<?php echo yii\helpers\Url::to(['site/logout'])?>" class="log">登出</a>
						
			<h1> <?php 	
					if($gamecheck <=0 ){
					echo "您是否是我们要找的那个有缘人呢~~~";
						}
			else { 					
					if($getToken == 0){
						echo "恭喜你，您就是我们要找的幸运儿！";
						}
				}?> </h1>
	</div>

	
	<div id="middle">
		<div id="left" >
			<h2 id="min" >
				<?php 	
				if($gamecheck <=0 ){}
					else{ 
						if($val == $ansNow){
							}
						else{
								echo $nowMin;
							}
					}?>
			</h2>
		</div>

		<div id="mid">
			<h2 id="to" >
				<?php 
					
					if($gamecheck <=0 ){}
					
					else{
						
						if($val == $ansNow)
						{
							echo $ansNow;
						}
						else{
							echo "到";
						}
					}?>
			</h2>
		</div>

		<div id="right" >
			<h2 id="max" >
				<?php 
					if($gamecheck <=0 ){}
					
					else{
						if($val == $ansNow){}
						
						else{
							$nowMax = $gamedata->max_value;
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
			请输入您的号码。
		<br />
		<input type="text" maxlength="2" name="value" id="value" placeholder="1 - 99" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  autofocus />
		<input type="button" class="btn" value="提交" id="btnSubmit" onclick="verifyorder(document.getElementById('value').value)" />
		</p>
		<hr>
	</div>
		<div class="rule">

			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapses" aria-expanded="false" aria-controls="collapses">游戏规则  </button>

			
			<div class="collapse" id="collapses">
				 <div>
					在1 - 99 之间猜个数字。每位用户只能有<mark><b>5</b></mark>次机会，您只需要猜中系统给予的号码，就能领取我们派送的奖励。
				<br>
					第一次直接中奖将获得奖励10元<br>
					第二次中奖将获得奖励5元<br>
					第三,四,五次中奖将获得奖励2元。<br>
				</div>
			</div>
		</div>
			<div class="chg">
			<br>
			<?php 
				if($gamecheck >=5 || $today == $date){	
						echo "您今天的次数已达成。请明天再来。";
					}
				elseif($gamecheck <5)
				{
			?>
				您还有 <?php echo 5-$gamecheck ?> 次机会哟。
			<?php 
				}
			?>
			

			</div>

			<h5 id="copyright"></h5>
</div>

</body>

</html>




