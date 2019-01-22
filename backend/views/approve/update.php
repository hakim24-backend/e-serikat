<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = 'Update Data : ' . $model->id;;
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="approve-update">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <?php $form = ActiveForm::begin([
    	'options'=>[
    	'enctype' => 'multipart/form-data'
    	]
    ]); ?>

    <?= $form->field($model, 'description')->textInput()->label('Deskripsi') ?>

<!--     <?= $form->field($model, 'responsibility_value')->textInput() ?> -->

     <!-- <?= $form->field($model, 'file')->FileInput(); ?> -->


     <!-- <?= $form->field($model, 'photo')->FileInput()->label('Foto') ?> -->

     <label>File</label><?= 
		FileInput::widget([
		    'name' => 'file',

		    'options' => [
	            'multiple' => true,
	            'allowedFileExtensions'=>['pdf'],
	            ],
	        'pluginOptions' => [
	            'showPreview' => false,
	            'showCaption' => true,
	            'showRemove' => true,
	            'showUpload' => false
	            ],
		]);
	?><br>

	<label>Foto</label><?= 
		FileInput::widget([
		    'name' => 'photo',

		    'options' => [
	            'multiple' => true,
	            'allowedFileExtensions'=>['jpg','png'],
	            ],
	        'pluginOptions' => [
	            'showPreview' => false,
	            'showCaption' => true,
	            'showRemove' => true,
	            'showUpload' => false
	            ],
		]);
	?><br>



<!--      <?= $form->field($model, 'activity_id')->textInput() ?>  -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
