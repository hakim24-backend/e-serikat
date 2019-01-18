<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Department */
/* @var $form yii\widgets\ActiveForm */
$this->title = ' Relokasi Dana ' . Yii::$app->user->identity->username;
?>

<div class="relokasi-form">

	<h3> Sumber </h3>

    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true]); ?>

<!--         <?= Html::activeDropDownList($model, 'id',$item) ?> -->

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true])->DropDownList(['Sekretariat','Ketua','Department','Seksi'],
        [ 
        	'prompt' => 'Pilih Jenis SDM'
        ])->label('Jenis SDM') ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true])->label('Kode Anggaran') ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true])->label('Value Sekarang') ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true])->label('Jumlah Dana') ?>

    <?php ActiveForm::end(); ?>

</div>

<div class="relokasi-form">

	<h3> Tujuan </h3>

    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true]); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true])->label('Jenis SDM'); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true])->label('Kode Anggaran'); ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>