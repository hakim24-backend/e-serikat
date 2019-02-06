<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="approve-form">

    <?php $form = ActiveForm::begin([
    	'options'=>[
    	'enctype' => 'multipart/form-data'
    	]
    ]); ?>

    <?= $form->field($model, 'description')->textArea(['row' => 5])->label('Deskripsi') ?>

<!--     <?= $form->field($model, 'responsibility_value')->textInput() ?> -->

     <!-- <?= $form->field($model, 'file')->FileInput(); ?> -->


     <!-- <?= $form->field($model, 'photo')->FileInput()->label('Foto') ?> -->

    <?= $form->field($model, 'fileApprove')->widget(FileInput::classname(), [
    'options' => [
    	'accept' => 'application/*',
	    'multiple' => true,
	    'required' => 'required',
	    'allowedFileExtensions'=>['pdf','doc'],
    	],
    	'pluginOptions' => [
	    'showPreview' => false,
	    'showCaption' => true,
	    'showRemove' => true,
	    'showUpload' => false
	    ],
	]) ?>


	<?= $form->field($model, 'photoApprove')->widget(FileInput::classname(), [
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
