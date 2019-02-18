<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_daily_responsibility".
 *
 * @property int $id
 * @property string $description
 * @property double $responsibility_value
 * @property string $file
 * @property string $photo
 * @property int $activity_id
 *
 * @property ActivityDaily $activity
 */
class ActivityDailyResponsibility extends \yii\db\ActiveRecord
{

    public $fileApproves;
    public $photoApproves;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_daily_responsibility';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileApproves'], 'file', 'extensions' => 'pdf, doc', 'maxFiles' => 4],
            [['photoApproves'], 'file', 'extensions' => 'jpg, png, jpeg', 'maxFiles' => 4],
            [['description', 'responsibility_value', 'file', 'photo'], 'required'],
            [['description', 'file', 'photo'], 'string'],
            [['responsibility_value'], 'number'],
            [['activity_id'], 'integer'],
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
            'description' => 'Description',
            'responsibility_value' => 'Responsibility Value',
            'file' => 'File',
            'photo' => 'Photo',
            'activity_id' => 'Activity ID',
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
