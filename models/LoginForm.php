<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $userName;
    public $password;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['userName', 'password'], 'required' ,'on'=> ['login']],
            // rememberMe must be a boolean value
            //['rememberMe', 'boolean'],
            // password is validated by validatePassword()
			['password', 'validatePass','on'=> ['login']],
        ];
    }
	
	public function validatePass()
    {
      if(!$this->hasErrors()) {
        $data = self::find()->where('userName = :user and password = :pass',[":user" => $this->userName, ":pass"=> md5($this->password)])->one();
        if(is_null($data)) {
          $this->addError("userpass","用户名或者密码错误");
        }
      }
    }
 
    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login($data)
    {
		$this->scenario = "login";
        if ($this->load($data)  && $this->validate()) {
           
        }
        return false;
    }
	

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
   
}
