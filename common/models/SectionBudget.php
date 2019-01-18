<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "section_budget".
 *
 * @property int $id
 * @property int $section_budget_id
 * @property double $section_budget_value
 * @property int $section_id
 * @property string $section_budget_code
 *
 * @property ActivityBudgetSection[] $activityBudgetSections
 * @property ActivityDailyBudgetSection[] $activityDailyBudgetSections
 * @property Budget $sectionBudget
 * @property Section $section
 */
class SectionBudget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_budget_id', 'section_budget_value', 'section_id', 'section_budget_code'], 'required'],
            [['section_budget_id', 'section_id'], 'integer'],
            [['section_budget_value'], 'number'],
            [['section_budget_code'], 'string', 'max' => 255],
            [['section_budget_id'], 'exist', 'skipOnError' => true, 'targetClass' => Budget::className(), 'targetAttribute' => ['section_budget_id' => 'id']],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::className(), 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_budget_id' => 'Section Budget ID',
            'section_budget_value' => 'Section Budget Value',
            'section_id' => 'Section ID',
            'section_budget_code' => 'Section Budget Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetSections()
    {
        return $this->hasMany(ActivityBudgetSection::className(), ['section_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetSections()
    {
        return $this->hasMany(ActivityDailyBudgetSection::className(), ['section_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSectionBudget()
    {
        return $this->hasOne(Budget::className(), ['id' => 'section_budget_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id' => 'section_id']);
    }
}
