<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;
$Role = Yii::$app->user->identity->roleName();
function to_rp($val)
{
    return "Rp " . number_format($val,0,',','.');
}

/* @var $this yii\web\View */
/* @var $model common\models\Approve */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="approve-form">
  <div class="col-sm-12">
    <label>Dana Budget Sekarang : </label>
    <?php if ($Role == "Departemen") { ?>
        <div id="budget_now">
            <?= to_rp($baru->department_budget_value) ?>
        </div><br>
    <?php } elseif ($Role == "Seksi") { ?>
        <?= to_rp($baru->section_budget_value) ?>
   <?php } ?>
  </div>
    <br>
  <div class="col-sm-12">
    <label>Dana Yang diajukan : </label>
    <div>
        <?= to_rp($modelBudget->budget_value_sum) ?>
    </div><br>
  </div>
  <br>
  <br>
    <?php $form = ActiveForm::begin([
        'options'=>[
        'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?php
        echo $form->field($modelBudget, 'budget_value_dp')->widget(\kartik\money\MaskMoney::classname(), [
            'pluginOptions' => [
            'prefix' => 'Rp ',
            'thousands' => '.',
            'decimal' => ',',
            'precision' => 0
            ],
            'options' => [
                //'disabled' => true,
            ]
        ])->label('Realisasi Dana');
        ?>
    <!-- <?= $form->field($modelBudget, 'budget_value_dp')->textInput(['required'=>true])->label('Realisasi Dana') ?> -->

    <?= $form->field($model, 'description')->textArea(['row' => 5])->label('Deskripsi') ?>

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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-save']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
  
$this->registerJs("

  $('.btn-save').on('click',function(){
    var id_budget_now = $('#budget_now').text();
    var budget_fix  = id_budget_now.replace('Rp ','').replace(/\./g,'');
    var id_realisasi = $('#activitybudgetdepartment-budget_value_dp-disp').val();
    var realisasi_fix = id_realisasi.replace('Rp ','').replace(/\./g,'');

    if(BigInt(budget_fix) < BigInt(realisasi_fix)){
        alert('Realisasi dana tidak boleh melebihi dana budget sekarang');
        $('#activitybudgetdepartment-budget_value_dp-disp').focus();
        return false;
    }

  });
 
");

?>
