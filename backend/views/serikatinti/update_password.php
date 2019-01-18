<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper; 
use app\models\Rule; 
use app\models\MTipeKaryawan;
use app\models\MProyek; 
use app\models\MPerusahaan; 
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Ubah Pasword : ' . Yii::$app->user->identity->username;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true])->label('Password Baru') ?>
    <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true])->label('Password Sekarang') ?>

    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
