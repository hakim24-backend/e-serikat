<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "secretariat_budget".
 *
 * @property int $id
 * @property int $secretariat_budget_id
 * @property double $secretariat_budget_value
 * @property int $secretariat_id
 * @property string $secretariat_budget_code
 *
 * @property ActivityBudgetSecretariat[] $activityBudgetSecretariats
 * @property ActivityDailyBudgetSecretariat[] $activityDailyBudgetSecretariats
 * @property Budget $secretariatBudget
 * @property Secretariat $secretariat
 */
class SecretariatBudget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'secretariat_budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['secretariat_budget_id', 'secretariat_budget_value', 'secretariat_id', 'secretariat_budget_code'], 'required'],
            [['secretariat_budget_id', 'secretariat_id'], 'integer'],
            [['secretariat_budget_value'], 'number'],
            [['secretariat_budget_code'], 'string', 'max' => 255],
            [['secretariat_budget_id'], 'exist', 'skipOnError' => true, 'targetClass' => Budget::className(), 'targetAttribute' => ['secretariat_budget_id' => 'id']],
            [['secretariat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Secretariat::className(), 'targetAttribute' => ['secretariat_id' => 'id']],
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
            'secretariat_budget_value' => 'Secretariat Budget Value',
            'secretariat_id' => 'Secretariat ID',
            'secretariat_budget_code' => 'Secretariat Budget Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetSecretariats()
    {
        return $this->hasMany(ActivityBudgetSecretariat::className(), ['secretariat_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetSecretariats()
    {
        return $this->hasMany(ActivityDailyBudgetSecretariat::className(), ['secretariat_budget_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecretariatBudget()
    {
        return $this->hasOne(Budget::className(), ['id' => 'secretariat_budget_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecretariat()
    {
        return $this->hasOne(Secretariat::className(), ['id' => 'secretariat_id']);
    }
}
