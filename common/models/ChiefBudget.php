<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chief_budget".
 *
 * @property int $id
 * @property int $chief_budget_id
 * @property double $chief_budget_value
 * @property int $chief_id
 * @property string $chief_budget_code
 *
 * @property ActivityBudgetChief[] $activityBudgetChiefs
 * @property ActivityDailyBudgetChief[] $activityDailyBudgetChiefs
 * @property Budget $chiefBudget
 * @property Chief $chief
 */
class ChiefBudget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chief_budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chief_budget_id', 'chief_budget_value', 'chief_id', 'chief_budget_code'], 'required'],
            [['chief_budget_id', 'chief_id'], 'integer'],
            [['chief_budget_value'], 'number'],
            [['chief_budget_code'], 'string', 'max' => 255],
            [['chief_budget_id'], 'exist', 'skipOnError' => true, 'targetClass' => Budget::className(), 'targetAttribute' => ['chief_budget_id' => 'id']],
            [['chief_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chief::className(), 'targetAttribute' => ['chief_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chief_budget_id' => 'Chief Budget ID',
            'chief_budget_value' => 'Chief Budget Value',
            'chief_id' => 'Chief ID',
            'chief_budget_code' => 'Chief Budget Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetChiefs()
    {
        return $this->hasMany(ActivityBudgetChief::className(), ['chief_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetChiefs()
    {
        return $this->hasMany(ActivityDailyBudgetChief::className(), ['chief_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChiefBudget()
    {
        return $this->hasOne(Budget::className(), ['id' => 'chief_budget_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChief()
    {
        return $this->hasOne(Chief::className(), ['id' => 'chief_id']);
    }
}
