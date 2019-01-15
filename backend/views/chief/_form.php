<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Chief */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chief-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'chief_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chief_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_budget')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
