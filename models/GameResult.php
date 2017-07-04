<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_result".
 *
 * @property integer $resultID
 * @property integer $recordID
 * @property integer $userID
 * @property integer $success
 * @property string $successTime
 * @property integer $usedTimes
 * @property integer $reward
 */
class GameResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recordID', 'userID', 'successTime'], 'required'],
            [['recordID', 'userID', 'success', 'usedTimes', 'reward'], 'integer'],
            [['successTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'resultID' => Yii::t('app', 'Result ID'),
            'recordID' => Yii::t('app', 'Record ID'),
            'userID' => Yii::t('app', 'User ID'),
            'success' => Yii::t('app', 'Success'),
            'successTime' => Yii::t('app', 'Success Time'),
            'usedTimes' => Yii::t('app', 'Used Times'),
            'reward' => Yii::t('app', 'Reward'),
        ];
    }
}
