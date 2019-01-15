<?php
namespace common\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class Registrasi extends Model
{
    public $name;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'email'],
            ['name', 'string', 'max' => 255],
            ['name', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
        ];
    }

}
