<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDaily */
$this->title = 'Update Data Kegiatan Rutin Sekretariat';
$this->params['breadcrumbs'][] = ['label' => 'Activity Dailies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="activity-daily-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Data Kegiatan Rutin Sekretariat</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Nilai Anggaran Saat Ini</label>
                <div class="col-sm-8">
                    <?= $baru->secretariat_budget_value ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Uang Muka Anggaran</label>
                <div class="col-sm-8">
                    <?= $form->field($budget, 'budget_value_dp')->textInput( )->label(false); ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Nilai Anggaran</label>
                <div class="col-sm-8">
                    <?= $form->field($budget, 'budget_value_sum')->textInput( )->label(false); ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Judul</label>
                <div class="col-sm-8">
                    <?= $form->field($model, 'title')->textarea(['rows' => 6])->label(false); ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Deskripsi</label>
                <div class="col-sm-8">
                    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(false); ?>
                </div>
            </div>
        </div>
    </div>
</div>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
<div class="box box-info">
    <div class="box-body">
        
             <div class="" style="padding: 10px;">
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
                                'minDate' => date('Y-m-d',strtotime("-3 days")),
                                'maxDate' => date('Y-m-d',strtotime("+1 month")),
                            ]
                        ]) . $addon;
                        echo '</div>';
               ?>
              </div>
            </div>
        </div> 
    </div>
</div>

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
