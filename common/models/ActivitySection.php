<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_section".
 *
 * @property int $id
 * @property string $section_name
 * @property int $activity_id
 *
 * @property Activity $activity
 * @property ActivitySectionMember[] $activitySectionMembers
 */
class ActivitySection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['section_name', 'activity_id'], 'required'],
            // [['activity_id'], 'integer'],
            [['section_name'], 'string', 'max' => 255],
            // [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_name' => 'Section Name',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivitySectionMembers()
    {
        return $this->hasMany(ActivitySectionMember::className(), ['section_activity_id' => 'id']);
    }
}
