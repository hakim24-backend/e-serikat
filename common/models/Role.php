<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name_role
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_role'], 'required'],
            [['name_role'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_role' => 'Name Role',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['role' => 'id']);
    }

    public function getUsersOne()
    {
        return $this->hasOne(User::className(), ['role' => 'id']);
    }
}
