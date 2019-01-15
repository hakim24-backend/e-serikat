<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "section".
 *
 * @property int $id
 * @property string $section_name
 * @property int $id_depart
 * @property int $status_budget
 * @property string $section_code
 * @property int $user_id
 *
 * @property Department $depart
 * @property User $user
 * @property SectionBudget[] $sectionBudgets
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_name', 'id_depart', 'status_budget', 'section_code'], 'required'],
            [['id_depart', 'status_budget', 'user_id'], 'integer'],
            [['section_name', 'section_code'], 'string', 'max' => 255],
            [['id_depart'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['id_depart' => 'id']],
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
            'section_name' => 'Section Name',
            'id_depart' => 'Id Depart',
            'status_budget' => 'Status Budget',
            'section_code' => 'Section Code',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepart()
    {
        return $this->hasOne(Department::className(), ['id' => 'id_depart']);
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
    public function getSectionBudgets()
    {
        return $this->hasMany(SectionBudget::className(), ['section_id' => 'id']);
    }
}
