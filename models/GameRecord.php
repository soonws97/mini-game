<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game_record".
 *
 * @property integer $recordID
 * @property integer $record_1
 * @property integer $record_2
 * @property integer $record_3
 * @property integer $record_4
 * @property integer $record_5
 * @property integer $userID
 * @property string $playTime
 * @property integer $token
 * @property integer $min_value
 * @property integer $max_value
 * @property integer $playingNow
 * @property integer $ans
 */
class GameRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['record_1', 'record_2', 'record_3', 'record_4', 'record_5', 'userID', 'token', 'min_value', 'max_value', 'playingNow', 'ans'], 'integer'],
            [['userID', 'playingNow'], 'required'],
            [['playTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recordID' => Yii::t('app', 'Record ID'),
            'record_1' => Yii::t('app', 'Record 1'),
            'record_2' => Yii::t('app', 'Record 2'),
            'record_3' => Yii::t('app', 'Record 3'),
            'record_4' => Yii::t('app', 'Record 4'),
            'record_5' => Yii::t('app', 'Record 5'),
            'userID' => Yii::t('app', 'User ID'),
            'playTime' => Yii::t('app', 'Play Time'),
            'token' => Yii::t('app', 'Token'),
            'min_value' => Yii::t('app', 'Min Value'),
            'max_value' => Yii::t('app', 'Max Value'),
            'playingNow' => Yii::t('app', 'Playing Now'),
            'ans' => Yii::t('app', 'Ans'),
        ];
    }
}
