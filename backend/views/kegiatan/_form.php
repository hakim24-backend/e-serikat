<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */

/* @var $model common\models\Activity */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Data Kegiatan';

$range = date('Y-m-d').' to '.date('Y-m-d');
$range_start = date('Y-m-d');
$range_end = date('Y-m-d');
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Judul') ?>

    <?= $form->field($model, 'background')->textarea(['rows' => 6])->label('Latar Belakang') ?>

    <?= $form->field($model, 'purpose')->textarea(['rows' => 6])->label('Tujuan') ?>

    <?= $form->field($model, 'target_activity')->textarea(['rows' => 6])->label('Target Kegiatan') ?>

    <?= $form->field($model, 'place_activity')->textarea(['rows' => 6])->label('Tempat Kegiatan') ?>

    <?= $form->field($model, 'place_activity_x')->textarea(['rows' => 6])->label('Tempat Kegiatan X') ?>

    <?= $form->field($model, 'place_activity_y')->textarea(['rows' => 6])->label('Tempat Kegiatan Y') ?>

        <div class="">
               <label style="font-size: 14px;">Tanggal</label>
               <?php 
            $addon = <<< HTML
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
HTML;
                echo '<div class="input-group drp-container">';
                echo DateRangePicker::widget([
                    'name'=>'date_range',
                    'value'=>$range,
                    'useWithAddon'=>true,
                    'convertFormat'=>true,
                    'startAttribute' => 'from_date',
                    'endAttribute' => 'to_date',
                    'startInputOptions' => ['value' => $range_start],
                    'endInputOptions' => ['value' => $range_end],
                    'options' => [
                        'class' => 'form-control',
                    ],
                    'pluginOptions'=>[
                        'locale'=>[
                            'format' => 'Y-m-d'
                        ],
                        'drops'=>'up',
                        'minDate' => date('Y-m-d',strtotime("+1 weeks")),
                        'maxDate' => date('Y-m-d',strtotime("+1 month")),
                    ]
                ]) . $addon;
                echo '</div>';
               ?>
        </div>
        <br>

    <!-- <?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'finance_status')->textInput() ?>

    <?= $form->field($model, 'department_status')->textInput() ?>

    <?= $form->field($model, 'chief_status')->textInput() ?>

    <?= $form->field($model, 'chief_code_id')->textInput() ?>

    <?= $form->field($model, 'department_code_id')->textInput() ?>

    <?= $form->field($model, 'done')->textInput() ?> -->
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
