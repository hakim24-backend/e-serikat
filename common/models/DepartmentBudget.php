<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "department_budget".
 *
 * @property int $id
 * @property int $department_budget_id
 * @property double $department_budget_value
 * @property int $department_id
 * @property string $department_budget_code
 *
 * @property ActivityBudgetDepartment[] $activityBudgetDepartments
 * @property ActivityDailyBudgetDepart[] $activityDailyBudgetDeparts
 * @property Budget $departmentBudget
 * @property Department $department
 */
class DepartmentBudget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department_budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_budget_id', 'department_budget_value', 'department_id', 'department_budget_code'], 'required'],
            [['department_budget_id', 'department_id'], 'integer'],
            [['department_budget_value'], 'number'],
            [['department_budget_code'], 'string', 'max' => 255],
            [['department_budget_id'], 'exist', 'skipOnError' => true, 'targetClass' => Budget::className(), 'targetAttribute' => ['department_budget_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department_budget_id' => 'Department Budget ID',
            'department_budget_value' => 'Department Budget Value',
            'department_id' => 'Department ID',
            'department_budget_code' => 'Department Budget Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetDepartments()
    {
        return $this->hasMany(ActivityBudgetDepartment::className(), ['department_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetDeparts()
    {
        return $this->hasMany(ActivityDailyBudgetDepart::className(), ['department_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentBudget()
    {
        return $this->hasOne(Budget::className(), ['id' => 'department_budget_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }
}
