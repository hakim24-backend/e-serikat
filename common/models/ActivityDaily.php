<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_daily".
 *
 * @property int $id
 * @property int $finance_status
 * @property int $department_status
 * @property int $chief_status
 * @property int $chief_code_id
 * @property int $department_code_id
 * @property string $title
 * @property string $description
 * @property int $role
 * @property string $date
 * @property int $done
 *
 * @property Chief $chiefCode
 * @property Department $departmentCode
 * @property Role $role0
 * @property ActivityDailyBudgetChief[] $activityDailyBudgetChiefs
 * @property ActivityDailyBudgetDepart[] $activityDailyBudgetDeparts
 * @property ActivityDailyBudgetSecretariat[] $activityDailyBudgetSecretariats
 * @property ActivityDailyBudgetSection[] $activityDailyBudgetSections
 * @property ActivityDailyResponsibility[] $activityDailyResponsibilities
 */
class ActivityDaily extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_daily';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['finance_status', 'department_status', 'chief_status', 'chief_code_id', 'department_code_id', 'title', 'description', 'role', 'date_start','date_end'], 'required'],
            [['finance_status', 'department_status', 'chief_status', 'chief_code_id', 'department_code_id', 'role', 'done'], 'integer'],
            [['description'], 'string'],
            // [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['chief_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chief::className(), 'targetAttribute' => ['chief_code_id' => 'id']],
            [['department_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_code_id' => 'id']],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'finance_status' => 'Finance Status',
            'department_status' => 'Department Status',
            'chief_status' => 'Chief Status',
            'chief_code_id' => 'Chief Code ID',
            'department_code_id' => 'Department Code ID',
            'title' => 'Title',
            'description' => 'Description',
            'role' => 'Role',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'done' => 'Done',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChiefCode()
    {
        return $this->hasOne(Chief::className(), ['id' => 'chief_code_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentCode()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_code_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole0()
    {
        return $this->hasOne(Role::className(), ['id' => 'role']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetChiefs()
    {
        return $this->hasMany(ActivityDailyBudgetChief::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetDeparts()
    {
        return $this->hasMany(ActivityDailyBudgetDepart::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetSecretariats()
    {
        return $this->hasMany(ActivityDailyBudgetSecretariat::className(), ['activity_id' => 'id']);
    }

    public function getActivityDailyBudgetSecretariatsOne()
    {
        return $this->hasOne(ActivityDailyBudgetSecretariat::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyBudgetSections()
    {
        return $this->hasMany(ActivityDailyBudgetSection::className(), ['activity_id' => 'id']);
    }

    public function getActivityDailyBudgetSectionsOne()
    {
        return $this->hasOne(ActivityDailyBudgetSection::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityDailyResponsibilities()
    {
        return $this->hasMany(ActivityDailyResponsibility::className(), ['activity_id' => 'id']);
    }
}
