<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "budget".
 *
 * @property int $id
 * @property string $budget_code
 * @property string $budget_year
 * @property string $budget_name
 * @property double $budget_value
 *
 * @property ChiefBudget[] $chiefBudgets
 * @property DepartmentBudget[] $departmentBudgets
 * @property SecretariatBudget[] $secretariatBudgets
 * @property SectionBudget[] $sectionBudgets
 */
class Budget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['budget_code', 'budget_year', 'budget_name', 'budget_value','budget_rek'], 'required'],
            [['budget_value','budget_rek'], 'number'],
            [['budget_code', 'budget_year', 'budget_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'budget_code' => 'Budget Code',
            'budget_year' => 'Budget Year',
            'budget_name' => 'Budget Name',
            'budget_value' => 'Budget Value',
            'budget_rek' => 'Budget Rek',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChiefBudgets()
    {
        return $this->hasMany(ChiefBudget::className(), ['chief_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentBudgets()
    {
        return $this->hasMany(DepartmentBudget::className(), ['department_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecretariatBudgets()
    {
        return $this->hasMany(SecretariatBudget::className(), ['secretariat_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionBudgets()
    {
        return $this->hasMany(SectionBudget::className(), ['section_budget_id' => 'id']);
    }
}
