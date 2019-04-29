<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */
/* @var $form yii\widgets\ActiveForm */
$Role = Yii::$app->user->identity->roleName();
function to_rp($val)
{
    return "Rp " . number_format($val,0,',','.');
}
?>

<div class="approve-form">

  <div class="col-sm-12">
    <label>Dana Budget Sekarang : </label>
    <?php if ($Role == "Sekretariat") { ?>
        <?= to_rp($baru->secretariat_budget_value) ?>
    <?php } elseif ($Role == "Seksi") { ?>
        <?= to_rp($baru->section_budget_value) ?>
   <?php } ?>
  </div>
  <br>
  <br>

    <?php $form = ActiveForm::begin([
    	'options'=>[
    	'enctype' => 'multipart/form-data'
    	]
    ]); ?>

    <?php
    echo $form->field($modelBudget, 'budget_value_dp')->widget(MaskMoney::classname(), [
        'pluginOptions' => [
        'prefix' => 'Rp ',
        'thousands' => '.',
        'decimal' => ',',
        'precision' => 0
        ],
        'options' => [
            'required'=>'required'
        ]
    ])->label('Realisasi Dana');
    ?>

    <?= $form->field($model, 'description')->textArea(['row' => 5])->label('Uraian') ?>

<!--     <?= $form->field($model, 'responsibility_value')->textInput() ?> -->

     <!-- <?= $form->field($model, 'file')->FileInput(); ?> -->


     <!-- <?= $form->field($model, 'photo')->FileInput()->label('Foto') ?> -->

    <?= $form->field($model, 'fileApproves[]')->widget(FileInput::classname(), [
    'options' => [
    	'accept' => 'application/*',
	    'multiple' => true,
	    // 'required' => 'required',
	    'allowedFileExtensions'=>['pdf','doc','docx'],
    	],
    	'pluginOptions' => [
	    'showPreview' => false,
	    'showCaption' => true,
	    'showRemove' => true,
	    'showUpload' => false
	    ],
	]) ?>


	<?= $form->field($model, 'photoApproves[]')->widget(FileInput::classname(), [
    'options' => [
    	'accept' => 'image/*',
	    'multiple' => true,
	    'required' => 'required',
	    'allowedFileExtensions'=>['jpg','png','jpeg'],
    	],
    	'pluginOptions' => [
	    'showPreview' => false,
	    'showCaption' => true,
	    'showRemove' => true,
	    'showUpload' => false
	    ],
	]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
