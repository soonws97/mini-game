<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\GameRecord;
use app\models\GameResult;
use app\models\SGGameRewardBalance;
use app\models\ContactForm;



class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new User();
        if ( $model->login(Yii::$app->request->post())) {
			
            return $this->render('minigame');
			Yii::$app->end();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
		Yii::$app->session->removeAll();
		
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	public function actionMinigame()
	{
		$model = new User();
		if ( $model->login(Yii::$app->request->post())) {
			
            return $this->render('minigame');
			Yii::$app->end();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
	}
	
	public function actionKey()
	
	{
		$today = date('Y-m-d');//获得日期
		$t	= date('G:i:s');//获得时间
		//date('Y-m-d-G-i-s'); year - month - day - 24hour - minutes - second
		$userName =Yii::$app->session['userName'];//获得用户名
		$user = User::find()->where('userName = :name' ,[':name' => $userName])->one();	//以用户名找用户ID
		$uid = $user->userID;
		$userdata = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one();//获得用户资料

		$gamecheck = $user->gameCheck;//游玩次数
		$gamecount = $user;//全部关于那用户的
		//$ans = rand(2,98);
		$ans = 49;

		$min = 1;
		$max = 99;
		$data = Yii::$app->request->post();	
		$y = $data['record'];
		$small = 1;
		$big = 99;
		$i = 0;

			if (Yii::$app->request->isAjax)
			{
				//===========================================   create first record  ======================================================
				$sgBalance = SGGameRewardBalance::find()->where('sg_reward_id = :gameid',[':gameid' => 1 ])->one()->sg_balance;
				if($sgBalance <=100 ){
								return false;
							}
							
				if(empty($userdata)){
					
						if($y <=1 || $y >=99 ){
								return false;
								
							}
						//create new data
						$model = new GameRecord;
						$model->ans=$ans;
						$model->playDate = $today;
						$model->playTime = $t;
						$model->record_1 = $y;	
						$model->playingNow = $y;
						$model->userID = $uid;
						$model->load($data);
						$model->save();
						//For detect and adding bigger and smaller answer into database.
							if($y != $ans){
							//user ans bigger than ans
								if($y > $ans){
									$big=$y;
									$model->max_value=$big;
								}
								//user ans smaller than ans
								elseif($y < $ans){
									$small=$y;
									$model->min_value=$small;

								}
							//insert value to model, and save to database							
							$model->load($data);
							$model->save(false);
							$gamecount->gameCheck = 1;
							$gamecount->save(false);
							
							var_dump('000');
							
							
						}
						
						//if user straight correct
						elseif ($y == $ans){
							
							$model->token=0;
							$model->load($data);
							$model->save(false);
							$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
							$reward += 10;
							$gamecount->SGreward = $reward;
							$gamecount->gameCheck = 5;
							$gamecount->load($data);
							$gamecount->save(false);
							
							//for result table
							$result = new GameResult;		
							$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playDate;
							$recordID = GameRecord::find()->where('userID = :id and playDate = :pd' , [':id' => $uid , ':pd'=> $wontime ])->one()->recordID;
							$result->recordID = $recordID;
							$result->userID = $uid;
							$result->success = 1;
							$result->successTime = date('Y-m-d G-i-s');
							$result->usedTimes = 1;
							$result->reward = 10;
							$result->load($data);
							$result->save(false);
						}

					}
					//============================= if user data not empty ================================
					else{
					
						//$userDate = Yii::$app->formatter->asDate($time, 'yyyy-MM-dd');
						$userDate = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->orderBy(['playDate'=>SORT_DESC])->one()->playDate;
						//$userDate = Yii::$app->formatter->asDate($time, 'yyyy-MM-dd');
						//$userDate = date("Y-m-d", strtotime($time)); 和上面一样用法
						
						//=========================================== detect record  ======================================================
						if($gamecheck == 0 && $userDate != $today){	
								if($y <=1 || $y >=99 ){
								return false;
								}
								
								//give ans into database
								$model = new GameRecord;
								$model->ans=$ans;
								$model->userID = $uid;
								$model->playDate = $today;
								$model->playTime = $t;	
								$model->playingNow = $y;
								//$model->load($data);
								$model->save();
								//var_dump($model);exit;
								
							}
							
							$ans2 = GameRecord::find()->where('userID = :id and playDate = :pd', [':id' => $uid , ':pd'=> $today ])->one()->ans;
							$userDate = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->orderBy(['playDate'=>SORT_DESC])->one()->playDate;
							
						if($y != $ans2){
					
							$large = GameRecord::find()->where('userID = :id and playDate = :pd', [':id' => $uid , ':pd'=> $today ])->one()->max_value;
							$mini = GameRecord::find()->where('userID = :id and playDate = :pd', [':id' => $uid , ':pd'=> $today ])->one()->min_value;
							if($y <=$mini || $y>=$large){
									return false;
								}
							
							$model = GameRecord::find()->where('userID = :id and playDate = :pd', [':id' => $uid , ':pd'=> $today ])->one();
							
							//common item start
							if($y > $ans2){
								$model->max_value=$y;}
							elseif($y < $ans2){
								$model->min_value=$y;}
								$model->playDate = $today;
								$model->playTime = $t;
								$model->playingNow = $y;
								$model -> userID = $uid;
								//commmon item end
								
							if($userDate == $today){
								switch($gamecheck){
									case 0:
										$model->record_1 = $y;
										$gamecount->gameCheck = 1;
										var_dump("000");
										break;
									case 1:
										$model->record_2 = $y;
										$gamecount->gameCheck = 2;
										var_dump("001");
										break;
									case 2:
										$model->record_3 = $y;
										$gamecount->gameCheck = 3;
										var_dump("002");
										break;
									case 3:
										$model->record_4 = $y;
										$gamecount->gameCheck = 4;
										var_dump("003");
										break;	
									case 4:
										$model->record_5 = $y;
										$gamecount->gameCheck = 5;
										var_dump("004");
										break;
								}
							}
							
							//$model->load($data);
							$model->save(false);
							//$gamecount->load($data);
							$gamecount->save(false);
							
						}
							
					elseif($y == $ans2){
								
								$model = GameRecord::find()->where('userID = :id and playDate = :pd', [':id' => $uid , ':pd'=> $today ])->one();
								$model->playingNow = $y;
								
								$model -> userID = $uid;
								$model->token=0;
								
								$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
								$result = new GameResult;		
								$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playDate;
								$recordID = GameRecord::find()->where('userID = :id and playDate = :pd' , [':id' => $uid , ':pd'=> $wontime ])->one()->recordID;
								$result->recordID = $recordID;
								$result->userID = $uid;
								$result->success = 1;
								$result->successTime = date('Y-m-d G-i-s');
								
								$sgData = SGGameRewardBalance::find()->where('sg_reward_id = :gameid',[':gameid' => 1 ])->one();
								$sgBalance = SGGameRewardBalance::find()->where('sg_reward_id = :gameid',[':gameid' => 1 ])->one()->sg_balance;
								
							if($userDate == $today){
								switch($gamecheck){
									case 0:
										$model->record_1 = $y;
										$reward +=10;
										$result->usedTimes = 1;
										$result->reward = 10;
										$sgBalance = $sgBalance - 10;
										$sgData->sg_negative_balance = 10;
										break;
									case 1:
										$model->record_2 = $y;
										$reward +=5;
										$result->usedTimes = 2;
										$result->reward = 5;
										$sgBalance = $sgBalance - 5;
										$sgData->sg_negative_balance = 10;
										break;
										
									case 2:
										$model->record_3 = $y;
										$reward +=2;
										$result->usedTimes = 3;
										$result->reward = 2;
										$sgBalance = $sgBalance - 2;
										$sgData->sg_negative_balance = 10;
										break;
									case 3:
										$model->record_4 = $y;
										$reward +=2;
										$result->usedTimes = 4;
										$result->reward = 2;
										$sgBalance = $sgBalance - 2;
										$sgData->sg_negative_balance = 10;
										break;
									case 4:
										$model->record_5 = $y;
										$reward +=2;
										$result->usedTimes = 5;
										$result->reward = 2;
										$sgBalance = $sgBalance - 2;
										$sgData->sg_negative_balance = 10;
										break;
										
								}
							}
							
							//$model->load($data);
							$model->save(false);
							
							//$result->load($data);
							$result->save(false);
							
							$gamecount->SGreward = $reward;
							$gamecount->gameCheck = 5;
							//$gamecount->load($data);
							$gamecount->save(false);

							$sgData->sg_balance = $sgBalance;
							//$sgData->load($data);
							$sgData->save(false);
						}	
					}//end 
			}
	}
}

				
				
				
				
				
				