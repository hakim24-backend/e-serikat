<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'background')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'purpose')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'target_activity')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'place_activity')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'place_activity_x')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'place_activity_y')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date_start')->textInput() ?>

    <?= $form->field($model, 'date_end')->textInput() ?>

    <?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'finance_status')->textInput() ?>

    <?= $form->field($model, 'department_status')->textInput() ?>

    <?= $form->field($model, 'chief_status')->textInput() ?>

    <?= $form->field($model, 'chief_code_id')->textInput() ?>

    <?= $form->field($model, 'department_code_id')->textInput() ?>

    <?= $form->field($model, 'done')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
