<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property int $id
 * @property string $title
 * @property string $background
 * @property string $purpose
 * @property string $target_activity
 * @property string $place_activity
 * @property string $place_activity_x
 * @property string $place_activity_y
 * @property string $date_start
 * @property string $date_end
 * @property int $role
 * @property int $finance_status
 * @property int $department_status
 * @property int $chief_status
 * @property int $chief_code_id
 * @property int $department_code_id
 * @property int $done
 *
 * @property Role $role0
 * @property Department $departmentCode
 * @property Chief $chiefCode
 * @property ActivityBudgetChief[] $activityBudgetChiefs
 * @property ActivityBudgetDepartment[] $activityBudgetDepartments
 * @property ActivityBudgetSecretariat[] $activityBudgetSecretariats
 * @property ActivityBudgetSection[] $activityBudgetSections
 * @property ActivityMainMember[] $activityMainMembers
 * @property ActivityReject[] $activityRejects
 * @property ActivityResponsibility[] $activityResponsibilities
 * @property ActivitySection[] $activitySections
 * @property ActivitySectionMember[] $activitySectionMembers
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'background', 'purpose', 'target_activity', 'place_activity', 'place_activity_x', 'place_activity_y', 'date_start', 'date_end', 'role', 'finance_status', 'department_status', 'chief_status', 'chief_code_id', 'department_code_id'], 'required'],
            [['background', 'purpose', 'target_activity', 'place_activity', 'place_activity_x', 'place_activity_y'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['role', 'finance_status', 'department_status', 'chief_status', 'chief_code_id', 'department_code_id', 'done'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role' => 'id']],
            [['department_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_code_id' => 'id']],
            [['chief_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chief::className(), 'targetAttribute' => ['chief_code_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'background' => 'Background',
            'purpose' => 'Purpose',
            'target_activity' => 'Target Activity',
            'place_activity' => 'Place Activity',
            'place_activity_x' => 'Place Activity X',
            'place_activity_y' => 'Place Activity Y',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'role' => 'Role',
            'finance_status' => 'Finance Status',
            'department_status' => 'Department Status',
            'chief_status' => 'Chief Status',
            'chief_code_id' => 'Chief Code ID',
            'department_code_id' => 'Department Code ID',
            'done' => 'Done',
        ];
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
    public function getDepartmentCode()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_code_id']);
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
    public function getActivityBudgetChiefs()
    {
        return $this->hasMany(ActivityBudgetChief::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetDepartments()
    {
        return $this->hasMany(ActivityBudgetDepartment::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetSecretariats()
    {
        return $this->hasMany(ActivityBudgetSecretariat::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityBudgetSections()
    {
        return $this->hasMany(ActivityBudgetSection::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityMainMembers()
    {
        return $this->hasMany(ActivityMainMember::className(), ['acitivity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityRejects()
    {
        return $this->hasMany(ActivityReject::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityResponsibilities()
    {
        return $this->hasMany(ActivityResponsibility::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivitySections()
    {
        return $this->hasMany(ActivitySection::className(), ['activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivitySectionMembers()
    {
        return $this->hasMany(ActivitySectionMember::className(), ['activity_id' => 'id']);
    }
}
