<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\Department */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Pemindahan Dana';
?>
<?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Sumber</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Sumber Dana</label>
                <div class="col-sm-8">
                    <?= Html::dropDownList('jenis_sdm_source', null, [4=>'Sekretariat',6=>'Ketua',7=>'Department',8=>'Seksi'], ['prompt' => 'Pilih Sumber Dana', 'class'=>'col-sm-8 form-control', 'id'=>'jenis-asal']) ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Kode Anggaran</label>
                <div class="col-sm-8">
                    <?= Html::dropDownList('source_sdm', null, [], ['prompt' => 'Pilih Kode Anggaran', 'class'=>'col-sm-8 form-control','id'=>'kode-asal']) ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div id="nilai-anggaran-source">

        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Nilai Anggaran</label>
                <div class="col-sm-8">
                    <!-- <?= Html::textInput('source_value', '', ['autofocus' => true, 'required'=>true, 'type'=>'number', 'step'=>'any', 'min'=>0, 'class'=>'col-sm-8 form-control', 'id'=>'value-budget']) ?> -->

                    <?php echo MaskMoney::widget([
                        'name' => 'source_value',
                        'value' => null,
                        'options' => [
                            'autofocus' => true, 
                            'required'=>true, 
                            'class'=>'col-sm-8 form-control nilai-anggaran', 
                            'id'=>'value-budget'
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
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Tujuan</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Sumber Dana</label>
                <div class="col-sm-8">
                    <?= Html::dropDownList('jenis_sdm_dest', null, [4=>'Sekretariat',6=>'Ketua',7=>'Department',8=>'Seksi'], ['prompt' => 'Pilih Sumber Dana', 'class'=>'col-sm-8 form-control', 'id'=>'jenis-tujuan']) ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-4">Kode Anggaran</label>
                <div class="col-sm-8">
                    <?= Html::dropDownList('dest_sdm', null, [], ['prompt' => 'Pilih Kode Anggaran', 'class'=>'col-sm-8 form-control', 'id'=>'kode-tujuan']) ?>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div id="nilai-anggaran-dest">

        </div>
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-save', 'name' => 'submit-button']) ?>
    <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
</div>

<?php ActiveForm::end(); ?>



<?php
$url = Yii::$app->urlManager->createUrl('/relokasi/kode-tujuan?id=');
$url2 = Yii::$app->urlManager->createUrl('/relokasi/nilai-anggaran');

$js=<<<js

$('#value-budget').on('change',function(){
    var nilaisekarang = $('#nilai-sekarang').text();
    var nilaianggaran = $('#value-budget').val();
    var tipe = $('#jenis-asal').val();
    var kode = $('#kode-asal').val();

    var res = parseInt(nilaisekarang.replace("Rp ","").replace(".",""));
    
    if(parseInt(nilaianggaran) > res){
      alert('Nilai Anggaran Lebih Besar dari Nilai Anggaran Saat Ini. Mohon ubah nilai yang diinputkan !');
    }

});

$('.btn-save').on('click',function(){
    var nilaisekarang = $('#nilai-sekarang').text();
    var nilaianggaran = $('#value-budget').val();
    var tipe = $('#jenis-asal').val();
    var kode = $('#kode-asal').val();

    var res = parseInt(nilaisekarang.replace("Rp ","").replace(".",""));
    
    if(parseInt(nilaianggaran) > res){
      alert('Nilai Anggaran Lebih Besar dari Nilai Anggaran Saat Ini. Mohon ubah nilai yang diinputkan !');
      $('#value-budget-disp').focus();
      return false;
    }

});

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
       $('#value-budget-disp').attr({
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
