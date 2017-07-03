<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\GameRecord;
use app\models\GameResult;
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
        Yii::$app->user->logout();

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
		
		if (Yii::$app->session['isLogin']){
			
			return $this->render('minigame');
				
		}
		
		$time = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->ans;
		return $this->render('index');
	}
	
	public function actionKey()
	
	{
		
		$today = date('Y-m-d');//获得时间
		//date('Y-m-d-G-i-s'); year - month - day - 24hour - minutes - second
		$userName =Yii::$app->session['userName'];//获得用户名
		$uid = User::find()->where('userName = :name' ,[':name' => $userName])->one()->userID;	//以用户名找用户ID
		$userdata = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one();//获得游戏时间
		$gamecheck = User::find()->where('userID = :id' , [':id' => $uid ])->one()->gameCheck;//游玩次数
		$gamecount = User::find()->where('userID = :id' , [':id' => $uid ])->one();//全部关于那id的
		$ans = rand(2,98);
		$min = 1;
		$max = 99;
		$data = Yii::$app->request->post();	
		$y = $data['record'];
		$a  = max($max,$y);
		$small = 1;
		$big = 99;
		$i = 0;

			if (Yii::$app->request->isAjax)
			{
				//===========================================   create first record  ======================================================
				if(empty($userdata)){

						if($y <=1 || $y >=99 ){
							var_dump("hi");return false;
						}
				
						//create new data
						$model = new GameRecord;
						$model->ans=$ans;
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
							$model->record_1 = $y;	
							$model->playingNow = $y;	
							$model->load($data);
							$model -> userID = $uid;
							$model->save(false);
							$gamecount->gameCheck = 1;
							$gamecount->save(false);
							
							$time = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
							$userDate = Yii::$app->formatter->asDate($time, 'yyyy-MM-dd');
							$ans2 = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->ans;
							
							var_dump('000');
							
							
						}
						
						//if user straight correct
						elseif ($y == $ans){
							
							$model->record_1 = $y;
							$model->playingNow = $y;
							$model -> userID = $uid;
							$model->load($data);
							$model->save(false);
							$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
							$reward += 10;
							$gamecount->SGreward = $reward;
							$gamecount->gameCheck = 5;
							$gamecount->save(false);
							
							//for result table
							$result = new GameResult;		
							$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
							$recordID = GameRecord::find()->where('userID = :id and playTime = :pt' , [':id' => $uid , ':pt'=> $wontime ])->one()->recordID;
							$result->recordID = $recordID;
							$result->userID = $uid;
							$result->success = 1;
							$result->successTime = $wontime;
							$result->usedTimes = 1;
							$result->reward = 10;
							$result->load($data);
							$result->save(false);
							$model->token=0;
							$model->load($data);
							$model->save(false);
						}

					}
					//============================= if user data not empty ================================
					else{
					
							
						$time = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
						$userDate = Yii::$app->formatter->asDate($time, 'yyyy-MM-dd');
						//$userDate = date("Y-m-d", strtotime($time)); 和上面一样用法
						
						//===========================================   first record  ======================================================
						if($gamecheck == 0 && $userDate != $today){	
							if($y <=1 || $y >=99 ){
								return false;
							}
						var_dump('001');
						$model = new GameRecord;
						$model->ans=$ans;
						$model->load($data);
						$model->save();
						if($y != $ans){
								if($y > $ans){$big=$y;
									$model->max_value=$big;}
								elseif($y < $ans){$small=$y;
									$model->min_value=$small;}
								$model->record_1 = $y;	
								$model->playingNow = $y;
								$model->load($data);
								$model -> userID = $uid;
								$model->save(false);
								$gamecount->gameCheck = 1;
								$gamecount->save(false);
							}
								
						elseif ($y == $ans){
							
								$model->record_1 = $y;
								$model->playingNow = $y;								
								$model -> userID = $uid;
								$model->load($data);
								$model->save(false);
								$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
								$reward += 10;
								$gamecount->SGreward = $reward;
								$gamecount->gameCheck = 5;
								$gamecount->save(false);
									
								$result = new GameResult;		
								$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
								$recordID = GameRecord::find()->where('userID = :id and playTime = :pt' , [':id' => $uid , ':pt'=> $wontime ])->one()->recordID;
								$result->recordID = $recordID;
								$result->userID = $uid;
								$result->success = 1;
								$result->successTime = $wontime;
								$result->usedTimes = 1;
								$result->reward = 10;
								$result->load($data);
								$result->save(false);
								$model->token=0;
								$model->load($data);
								$model->save(false);
							}
						
					}	
					
						//===========================================   second record  ======================================================
						elseif($gamecheck==1 && $userDate == $today){
								var_dump('002');
								$large = GameRecord::find()->where('userID = :id and playTime =:pt' , [':id' => $uid , ':pt'=> $time ])->one()->max_value;
								$mini = GameRecord::find()->where('userID = :id and playTime =:pt' , [':id' => $uid , ':pt'=> $time ])->one()->min_value;
								if($y <=$mini || $y>=$large){
									return false;
								}	
								$model = GameRecord::find()->where('userID = :id and playTime =:pt' , [':id' => $uid , ':pt'=> $time ])->one();
								$ans2 = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->ans;
								if($y != $ans2){
									if($y > $ans2){$big=$y;
										$model->max_value=$big;}
									elseif($y < $ans2){$small=$y;
										$model->min_value=$small;}
									$model->record_2 = $y;
									$model->playingNow = $y;
									$model->load($data);
									$model->userID = $uid;
									$model->save(false);
									$gamecount->gameCheck = 2;
									$gamecount->save(false);
								}
								elseif($y ==$ans2){
									
									$model->record_2 = $y;
									$model->playingNow = $y;
									$model -> userID = $uid;
									$model->load($data);
									$model->save(false);
									$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
									$reward += 5;
									$gamecount->SGreward = $reward;
									$gamecount->gameCheck = 5;
									$gamecount->save(false);
									
									$result = new GameResult;		
									$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
									$recordID = GameRecord::find()->where('userID = :id and playTime = :pt' , [':id' => $uid , ':pt'=> $wontime ])->one()->recordID;
									$result->recordID = $recordID;
									$result->userID = $uid;
									$result->success = 1;
									$result->successTime = $wontime;
									$result->usedTimes = 2;
									$result->reward = 5;
									$result->load($data);
									$result->save(false);
									$model->token=0;
									$model->load($data);
									$model->save(false);
								}
							
								
								
						}
							
						//===========================================   third record  ======================================================
						elseif($gamecheck==2 && $userDate == $today){
								var_dump('003');
								$model = GameRecord::find()->where('userID = :id and playTime =:pt' , [':id' => $uid , ':pt'=> $time ])->one();
								$ans2 = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->ans;
								if($y != $ans2){
									if($y > $ans2){$big=$y;
										$model->max_value=$big;}
									elseif($y < $ans2){$small=$y;
										$model->min_value=$small;}
									$model->record_3 = $y;
									$model->playingNow = $y;
									$model->load($data);
									$model->userID = $uid;
									$model->save(false);
									$gamecount->gameCheck = 3;
									$gamecount->save(false);
								}
								elseif($y ==$ans2){
									
									$model->record_3 = $y;
									$model->playingNow = $y;
									$model -> userID = $uid;
									$model->load($data);
									$model->save(false);
									$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
									$reward += 2;
									$gamecount->SGreward = $reward;
									$gamecount->gameCheck = 5;
									$gamecount->save(false);
									
									$result = new GameResult;		
									$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
									$recordID = GameRecord::find()->where('userID = :id and playTime = :pt' , [':id' => $uid , ':pt'=> $wontime ])->one()->recordID;
									$result->recordID = $recordID;
									$result->userID = $uid;
									$result->success = 1;
									$result->successTime = $wontime;
									$result->usedTimes = 1;
									$result->reward = 2;
									$result->load($data);
									$result->save(false);
									$model->token=0;
									$model->load($data);
									$model->save(false);
									}
								
						}
						
						//===========================================   fourth record  ======================================================
						elseif($gamecheck==3 && $userDate == $today){
								var_dump('004');
								$model = GameRecord::find()->where('userID = :id and playTime =:pt' , [':id' => $uid , ':pt'=> $time ])->one();
								$ans2 = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->ans;
								if($y != $ans2){
									if($y > $ans2){$big=$y;
										$model->max_value=$big;}
									elseif($y < $ans2){$small=$y;
										$model->min_value=$small;}
									$model->record_4 = $y;
									$model->playingNow = $y;
									$model->load($data);
									$model->userID = $uid;
									$model->save(false);
									$gamecount->gameCheck = 4;
									$gamecount->save(false);
								}
								elseif($y ==$ans2){
									
									$model->record_4 = $y;
									$model->playingNow = $y;
									$model -> userID = $uid;
									$model->load($data);
									$model->save(false);
									$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
									$reward += 2;
									$gamecount->SGreward = $reward;
									$gamecount->gameCheck = 5;
									$gamecount->save(false);
									
									$result = new GameResult;		
									$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
									$recordID = GameRecord::find()->where('userID = :id and playTime = :pt' , [':id' => $uid , ':pt'=> $wontime ])->one()->recordID;
									$result->recordID = $recordID;
									$result->userID = $uid;
									$result->success = 1;
									$result->successTime = $wontime;
									$result->usedTimes = 1;
									$result->reward = 2;
									$result->load($data);
									$result->save(false);
									$model->token=0;
									$model->load($data);
									$model->save(false);
								}
								
						}
						
						//===========================================   fifth record  ======================================================
						elseif($gamecheck==4 && $userDate == $today){
								var_dump('005');
								$model = GameRecord::find()->where('userID = :id and playTime =:pt' , [':id' => $uid , ':pt'=> $time ])->one();
								$ans2 = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->ans;
								if($y != $ans2){
									if($y > $ans2){$big=$y;
										$model->max_value=$big;}
									elseif($y < $ans2){$small=$y;
										$model->min_value=$small;}
									$model->record_5 = $y;
									$model->playingNow = $y;
									$model->load($data);
									$model->userID = $uid;
									$model->save(false);
									$gamecount->gameCheck = 5;
									$gamecount->save(false);
								}
								elseif($y ==$ans2){
									
									$model->record_5 = $y;
									$model->playingNow = $y;
									$model -> userID = $uid;
									$model->load($data);
									$model->save(false);
									$reward = User::find()->where('userID = :id' , [':id' => $uid ,])->one()->SGreward;
									$reward += 2;
									$gamecount->SGreward = $reward;
									$gamecount->gameCheck = 5;
									$gamecount->save(false);
									
									$result = new GameResult;		
									$wontime = GameRecord::find()->where('userID = :id' , [':id' => $uid ])->one()->playTime;
									$recordID = GameRecord::find()->where('userID = :id and playTime = :pt' , [':id' => $uid , ':pt'=> $wontime ])->one()->recordID;
									$result->recordID = $recordID;
									$result->userID = $uid;
									$result->success = 1;
									$result->successTime = $wontime;
									$result->usedTimes = 1;
									$result->reward = 2;
									$result->load($data);
									$result->save(false);
									$model->token=0;
									$model->load($data);
									$model->save(false);
								}
						}
										

					}
			}
}
}