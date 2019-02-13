<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_budget_chief".
 *
 * @property int $id
 * @property int $chief_budget_id
 * @property double $budget_value_dp
 * @property double $budget_value_sum
 * @property int $activity_id
 *
 * @property ChiefBudget $chiefBudget
 * @property Activity $activity
 */
class ActivityBudgetChief extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_budget_chief';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chief_budget_id', 'budget_value_dp', 'budget_value_sum', 'activity_id'], 'required'],
            [['chief_budget_id', 'activity_id'], 'integer'],
            [['budget_value_dp', 'budget_value_sum'], 'number'],
            [['chief_budget_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChiefBudget::className(), 'targetAttribute' => ['chief_budget_id' => 'id']],
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
            'chief_budget_id' => 'Chief Budget ID',
            'budget_value_dp' => 'Budget Value Dp',
            'budget_value_sum' => 'Budget Value Sum',
            'activity_id' => 'Activity ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChiefBudget()
    {
        return $this->hasOne(ChiefBudget::className(), ['id' => 'chief_budget_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
}
