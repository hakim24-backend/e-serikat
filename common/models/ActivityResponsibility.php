<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_responsibility".
 *
 * @property int $id
 * @property string $description
 * @property double $responsibility_value
 * @property string $file
 * @property string $photo
 * @property int $activity_id
 *
 * @property Activity $activity
 */
class ActivityResponsibility extends \yii\db\ActiveRecord
{
    public $fileApprove;
    public $photoApprove;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_responsibility';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileApprove'], 'file', 'extensions' => 'pdf, doc'],
            [['photoApprove'], 'file', 'extensions' => 'jpg, png, jpeg', 'maxFiles' => 3],
            [['description', 'responsibility_value', 'file', 'photo', 'activity_id'], 'required'],
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
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
}
