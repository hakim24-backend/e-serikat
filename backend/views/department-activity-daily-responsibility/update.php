<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;
$Role = Yii::$app->user->identity->roleName();

$this->title = 'Form Pertanggungjawaban';
$this->params['breadcrumbs'][] = ['label' => 'Activity Dailies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="approve-form">
  <div class="col-sm-12">
    <label>Dana Budget Sekarang : </label>
    <?php if ($Role == "Departemen") { ?>
        <?= $baru->department_budget_value ?>
    <?php } elseif ($Role == "Seksi") { ?>
        <?= $baru->section_budget_value ?>
   <?php } ?>
  </div>

  <br>
  <div class="col-sm-12">
    <label>Dana Yang diajukan : </label>
        <?= to_rp($modelBudget->budget_value_sum) ?>
  </div>

  
  <br>
  <br>
    <?php $form = ActiveForm::begin([
    	'options'=>[
    	'enctype' => 'multipart/form-data'
    	]
    ]); ?>
    <?= $form->field($modelBudget, 'budget_value_dp')->textInput(['required'=>true])->label('Realisasi Dana') ?>

    <?= $form->field($model, 'description')->textInput()->label('Deskripsi') ?>

<!--     <?= $form->field($model, 'responsibility_value')->textInput() ?> -->

     <!-- <?= $form->field($model, 'file')->FileInput(); ?> -->


     <!-- <?= $form->field($model, 'photo')->FileInput()->label('Foto') ?> -->

    <?= $form->field($model, 'fileApproves[]')->widget(FileInput::classname(), [
    'options' => [
    	'accept' => 'application/*',
	    'multiple' => true,
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
