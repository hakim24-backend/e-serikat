<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_section_member".
 *
 * @property int $id
 * @property string $section_name_member
 * @property int $section_activity_id
 * @property int $activity_id
 *
 * @property ActivitySection $sectionActivity
 * @property Activity $activity
 */
class ActivitySectionMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_section_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_name_member', 'section_activity_id', 'activity_id'], 'required'],
            [['section_activity_id', 'activity_id'], 'integer'],
            [['section_name_member'], 'string', 'max' => 255],
            [['section_activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => ActivitySection::className(), 'targetAttribute' => ['section_activity_id' => 'id']],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_name_member' => 'Section Name Member',
            'section_activity_id' => 'Section Activity ID',
            'activity_id' => 'Activity ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionActivity()
    {
        return $this->hasOne(ActivitySection::className(), ['id' => 'section_activity_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
}
