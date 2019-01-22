<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_budget_secretariat".
 *
 * @property int $id
 * @property int $secretariat_budget_id
 * @property double $budget_value_dp
 * @property double $budget_value_sum
 * @property int $activity_id
 *
 * @property Activity $activity
 * @property SecretariatBudget $secretariatBudget
 */
class ActivityBudgetSecretariat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_budget_secretariat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['secretariat_budget_id', 'budget_value_dp', 'budget_value_sum', 'activity_id'], 'required'],
            [['secretariat_budget_id', 'activity_id'], 'integer'],
            [['budget_value_dp', 'budget_value_sum'], 'number'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::className(), 'targetAttribute' => ['activity_id' => 'id']],
            [['secretariat_budget_id'], 'exist', 'skipOnError' => true, 'targetClass' => SecretariatBudget::className(), 'targetAttribute' => ['secretariat_budget_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'secretariat_budget_id' => 'Secretariat Budget ID',
            'budget_value_dp' => 'Budget Value Dp',
            'budget_value_sum' => 'Budget Value Sum',
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
    public function getSecretariatBudget()
    {
        return $this->hasOne(SecretariatBudget::className(), ['id' => 'secretariat_budget_id']);
    }
}
