<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transfer_record".
 *
 * @property int $id
 * @property string $code_source
 * @property double $value
 * @property string $code_dest
 */
class TransferRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transfer_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code_source', 'value', 'code_dest'], 'required'],
            [['value'], 'number'],
            [['code_source', 'code_dest'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code_source' => 'Code Source',
            'value' => 'Value',
            'code_dest' => 'Code Dest',
        ];
    }
}
