<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\Budget */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Data Sumber Dana';
// $this->params['breadcrumbs'][] = ['label' => 'Sumber Dana', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="budget-form">
    <div class="box box-primary">
        <div class="box-body">
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

            <?= $form->field($model, 'budget_name')->textInput(['maxlength' => true, 'required'=>true])->label('Nama Budget') ?>

            <?= $form->field($model, 'budget_value')->widget(MaskMoney::classname(), [
                'options' => [
                    'placeholder' => 'Masukkan Nilai Saldo',
                    'required'=>true,
                ],
                'pluginOptions' => [
                    'prefix' => 'Rp. ',
                    'suffix' => '',
                    'affixesStay' => true,
                    'thousands' => '.',
                    'decimal' => ',',
                    'precision' => 0, 
                    'allowZero' => false,
                    'allowNegative' => false,
                ]
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
