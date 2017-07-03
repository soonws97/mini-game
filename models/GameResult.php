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
            [['resultID', 'recordID', 'userID'], 'required'],
            [['resultID', 'recordID', 'userID', 'success', 'reward'], 'integer'],
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
            'reward' => Yii::t('app', 'Reward'),
        ];
    }
}
