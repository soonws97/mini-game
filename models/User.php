<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $userID
 * @property string $userName
 * @property string $password
 * @property integer $SGreward
 * @property integer $gameCheck
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName', 'password'], 'required'],
            [['SGreward', 'gameCheck'], 'integer'],
            [['userName'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 20],
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
            'SGreward' => Yii::t('app', 'Sgreward'),
            'gameCheck' => Yii::t('app', 'Game Check'),
        ];
    }
}
