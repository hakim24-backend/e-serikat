<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * This is the model class for table "secretariat".
 *
 * @property int $id
 * @property string $secretariat_code
 * @property string $secretariat_name
 * @property int $user_id
 *
 * @property User $user
 * @property SecretariatBudget[] $secretariatBudgets
 */
class Secretariat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'secretariat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['secretariat_code', 'secretariat_name', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['secretariat_code', 'secretariat_name'], 'string', 'max' => 255],
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
            'secretariat_code' => 'Secretariat Code',
            'secretariat_name' => 'Secretariat Name',
            'user_id' => 'User ID',
        ];
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
    public function getSecretariatBudgets()
    {
        return $this->hasMany(SecretariatBudget::className(), ['secretariat_id' => 'id']);
    }
}
