<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_daily_reject".
 *
 * @property int $id
 * @property int $activity_id
 * @property string $message
 *
 * @property ActivityDaily $activity
 */
class ActivityDailyReject extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_daily_reject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'message'], 'required'],
            [['activity_id'], 'integer'],
            [['message'], 'string'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => ActivityDaily::className(), 'targetAttribute' => ['activity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(ActivityDaily::className(), ['id' => 'activity_id']);
    }
}
