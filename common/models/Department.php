<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id
 * @property string $depart_name
 * @property int $id_chief
 * @property int $status_budget
 * @property string $depart_code
 * @property int $user_id
 *
 * @property Activity[] $activities
 * @property ActivityDaily[] $activityDailies
 * @property Chief $chief
 * @property User $user
 * @property DepartmentBudget[] $departmentBudgets
 * @property Section[] $sections
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['depart_name', 'id_chief', 'status_budget', 'depart_code', 'user_id'], 'required'],
            [['id_chief', 'status_budget', 'user_id'], 'integer'],
            [['depart_name', 'depart_code'], 'string', 'max' => 255],
            [['id_chief'], 'exist', 'skipOnError' => true, 'targetClass' => Chief::className(), 'targetAttribute' => ['id_chief' => 'id']],
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
            'depart_name' => 'Depart Name',
            'id_chief' => 'Id Chief',
            'status_budget' => 'Status Budget',
            'depart_code' => 'Depart Code',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activity::className(), ['department_code_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailies()
    {
        return $this->hasMany(ActivityDaily::className(), ['department_code_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChief()
    {
        return $this->hasOne(Chief::className(), ['id' => 'id_chief']);
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
    public function getDepartmentBudgets()
    {
        return $this->hasMany(DepartmentBudget::className(), ['department_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::className(), ['id_depart' => 'id']);
    }
}
