<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $userID
 * @property string $userName
 * @property string $password
 * @property integer $gameCheck
 */
class User extends \yii\db\ActiveRecord
{

	public $userName;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName', 'password'], 'required' ,'on'=> ['login']],
            [['gameCheck'], 'integer'],
            [['userName'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 20],
			['password', 'validatePass','on'=> ['login']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userID' => Yii::t('app', 'User ID'),
            'userName' => Yii::t('app', 'User Name'),
            'password' => Yii::t('app', 'Password'),
            'gameCheck' => Yii::t('app', 'Game Check'),
        ];
    }

	public function validatePass()
    {
      if(!$this->hasErrors()) {
        $data = self::find()->where('userName = :user and password = :pass',[":user" => $this->userName, ":pass"=>$this->password])->one();
        if(is_null($data)) {
          $this->addError("userpass","用户名或者密码错误");
        }
      }
    }


    public function login($data)
    {
        $this->scenario = "login";
        if ($this->load($data)  && $this->validate())
        {

            $session = Yii::$app->session;
            $session['userName'] = $this->userName;

            $session['isLogin'] = 1;
            return (bool)$session['isLogin'];
        }
        return false;
    }


}
