<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_main_member".
 *
 * @property int $id
 * @property string $name_committee
 * @property string $name_member
 * @property int $activity_id
 *
 * @property Activity $acitivity
 */
class ActivityMainMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_main_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['name_committee', 'name_member', 'activity_id'], 'required'],
            // [['activity_id'], 'integer'],
            [['name_committee', 'name_member'], 'string', 'max' => 255],
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
            'name_committee' => 'Name Committee',
            'name_member' => 'Name Member',
            'activity_id' => 'Acitivity ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcitivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
}
