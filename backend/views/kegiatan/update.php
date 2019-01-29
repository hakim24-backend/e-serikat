<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = 'Update Data';
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update '.$model->id;
?>
<div class="activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

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

<?php
$url = Yii::$app->urlManager->createUrl('/kegiatan-rutin/kode-tujuan?id=');
$url2 = Yii::$app->urlManager->createUrl('/kegiatan-rutin/nilai-anggaran-update');

$js=<<<js
$('#jenis-tujuan').on('change',function(){
    var tipe = $('#jenis-tujuan').val();
    $.ajax({
        url : "$url" + tipe,
        dataType : 'html',
        type : 'post'
    }).done(function(data){
       $('select#kode-tujuan').html(data);
    });
});

$('#jenis-asal').on('change',function(){
    var tipe = $('#jenis-asal').val();
    $.ajax({
        url : "$url" + tipe,
        dataType : 'html',
        type : 'post'
    }).done(function(data){
       $('select#kode-asal').html(data);
    });
});

$('#kode-asal').on('change',function(){
    var tipe = $('#jenis-asal').val();
    var kode = $('#kode-asal').val();
    $.ajax({
        url : "$url2",
        dataType : 'html',
        type : 'post',
        data: {
            tipe: tipe,
            kode: kode,
        },
    }).done(function(data){
        datas = JSON.parse(data);
       $('#nilai-anggaran-source').html(datas.message);
       $('#value-budget').attr({
           'max' : datas.max,
        });
    });
});

$('#kode-tujuan').on('change',function(){
    var tipe = $('#jenis-tujuan').val();
    var kode = $('#kode-tujuan').val();
    $.ajax({
        url : "$url2",
        dataType : 'html',
        type : 'post',
        data: {
            tipe: tipe,
            kode: kode,
        },
    }).done(function(data){
        datas = JSON.parse(data);
       $('#nilai-anggaran-source').html(datas.message);
    });
});

js;
$this->registerJs($js);
?>