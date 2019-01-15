<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chief".
 *
 * @property int $id
 * @property string $chief_name
 * @property string $chief_code
 * @property int $status_budget
 * @property int $user_id
 *
 * @property Activity[] $activities
 * @property ActivityDaily[] $activityDailies
 * @property User $user
 * @property ChiefBudget[] $chiefBudgets
 * @property Department[] $departments
 */
class Chief extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chief';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chief_name', 'chief_code', 'status_budget', 'user_id'], 'required'],
            [['status_budget', 'user_id'], 'integer'],
            [['chief_name', 'chief_code'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chief_name' => 'Chief Name',
            'chief_code' => 'Chief Code',
            'status_budget' => 'Status Budget',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['chief_code_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailies()
    {
        return $this->hasMany(ActivityDaily::className(), ['chief_code_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChiefBudgets()
    {
        return $this->hasMany(ChiefBudget::className(), ['chief_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['id_chief' => 'id']);
    }
}
