<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Budget */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budget-form">

    <?php $form = ActiveForm::begin(); ?>

<!--     <?= $form->field($model, 'budget_code')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($model, 'budget_year')->widget(DatePicker::classname(), [
    'options' => ['placeholder' => 'Masukkan Tahun ...'],
    'pluginOptions' => [
    	'format'=>'yyyy',
    	'minViewMode'=>'years',
        'autoclose'=>true
    ]
	])->label('Tahun Budget') ?>

    <?= $form->field($model, 'budget_name')->textInput(['maxlength' => true])->label('Nama Budget') ?>

    <?= $form->field($model, 'budget_value')->textInput()->label('Nilai Saldo') ?>

    <?= $form->field($model, 'budget_rek')->textInput()->label('Nilai Rekening') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
