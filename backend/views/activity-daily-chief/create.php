<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDaily */
/* @var $form yii\widgets\ActiveForm */
$range = date('Y-m-d') . ' to ' . date('Y-m-d');
$range_start = date('Y-m-d');
$range_end = date('Y-m-d');
$this->title = 'Buat Data Kegiatan Rutin';

$Role = Yii::$app->user->identity->roleName();
?>

<div class="activity-daily-form">

    <?php $form = ActiveForm::begin();?>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Data Kegiatan Rutin</h3>

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

                        <?=Html::dropDownList('jenis_sdm_source', null, [6 => 'Ketua'], ['prompt' => 'Pilih Sumber Dana', 'class' => 'col-sm-8 form-control', 'id' => 'jenis-asal'])?>

                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4">Kode Anggaran</label>
                    <div class="col-sm-8">
                        <?=Html::dropDownList('source_sdm', null, [], ['prompt' => 'Pilih Kode Anggaran', 'class' => 'col-sm-8 form-control', 'id' => 'kode-asal'])?>
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
                        <?php
                            
                            echo MaskMoney::widget([
                                'name' => 'source_value',
                                'value' => null,
                                'pluginOptions' => [
                                    'prefix' => 'Rp ',
                                    'thousands' => '.',
                                    'decimal' => ',',
                                    'precision' => 0
                                ],
                                'options' => [
                                'autofocus' => true, 
                                'required'=>true, 
                                'class'=>'col-sm-8 form-control nilai-anggaran', 
                                'id'=>'value-budget'
                                ]
                            ]);
                        ?>
                        <!-- <?=Html::textInput('source_value', '', ['autofocus' => true, 'required' => true, 'type' => 'number', 'step' => 'any', 'min' => 0, 'class' => 'col-sm-8 nilai-anggaran', 'id' => 'value-budget'])?> -->
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4">Judul</label>
                    <div class="col-sm-8">
                        <?=Html::textInput('judul', '', ['autofocus' => true, 'required' => true, 'type' => 'text', 'class' => 'col-sm-8 form-control', 'id' => 'judul'])?>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4">Deskripsi</label>
                    <div class="col-sm-8">
                        <?=Html::textarea('description', '', ['autofocus' => true, 'required' => true, 'type' => 'textarea', 'class' => 'col-sm-8 form-control', 'id' => 'description'])?>
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
    'name' => 'date_range',
    'value' => $range,
    'useWithAddon' => true,
    'convertFormat' => true,
    'startAttribute' => 'from_date',
    'endAttribute' => 'to_date',
    'startInputOptions' => ['value' => $range_start],
    'endInputOptions' => ['value' => $range_end],
    'options' => [
        'class' => 'form-control',
    ],
    'pluginOptions' => [
        'locale' => [
            'format' => 'Y-m-d',
        ],
        'drops' => 'up',
        'minDate' => date('Y-m-d',strtotime("0 days")),
        // 'maxDate' => date('Y-m-d',strtotime("+1 month")),
    ],
]) . $addon;
echo '</div>';
?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <?=Html::submitButton('Save', ['class' => 'btn btn-success btn-save'])?>
    <a class="btn btn-danger" href="<?=Url::to(Yii::$app->request->referrer);?>">Batal</a>
</div>
<?php ActiveForm::end();?>
</div>

<?php
$url = Yii::$app->urlManager->createUrl('/kegiatan-rutin/kode-tujuan?id=');
$url2 = Yii::$app->urlManager->createUrl('/kegiatan-rutin/nilai-anggaran');

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


$('#value-budget').on('change',function(){
  var nilaisekarang = $('#nilai-sekarang').text();
  var nilaianggaran = $('#value-budget').val();
  var tipe = $('#jenis-asal').val();
  var kode = $('#kode-asal').val();

  var res = nilaisekarang.replace("Rp ","").replace(/\./g,"");
  
  if(BigInt(nilaianggaran) > BigInt(res)){
      alert('Nilai Anggaran Lebih Besar dari Nilai Anggaran Saat Ini. Mohon ubah nilai yang diinputkan !');  
  }

});

$(".btn-save").on('click', function(){
  var nilaisekarang = $('#nilai-sekarang').text();
  var nilaianggaran = $('#value-budget').val();
  var tipe = $('#jenis-asal').val();
  var kode = $('#kode-asal').val();

  var res = nilaisekarang.replace("Rp ","").replace(/\./g,"");
  
  if(BigInt(nilaianggaran) > BigInt(res)){
      alert('Nilai Anggaran Lebih Besar dari Nilai Anggaran Saat Ini. Mohon ubah nilai yang diinputkan !');
      $('#value-budget-disp').focus();
      return false;
  }
});

$('.uang-muka ').on('change',function(){
    var uangmuka = $('.uang-muka').val();
    var nilaisekarang = $('#nilai-sekarang').text();
    var nilaianggaran = $('.nilai-anggaran').val();
    var tipe = $('#jenis-asal').val();
    var kode = $('#kode-asal').val();

    var res = parseInt(nilaisekarang.replace("Rp.",""));
    if(parseInt(uangmuka) > res){
      alert('Uang Muka Lebih Besar dari Nilai Anggaran Saat Ini. Mohon ubah nilai yang diinputkan !');
      $('.nilai-anggaran').val('0');
    }
    if(parseInt(uangmuka) > parseInt(nilaianggaran)){
      alert('Uang Muka Lebih Besar dari Anggaran Yang Diajukan. Mohon ubah nilai yang diinputkan !');
      $('.nilai-anggaran').val('0');
    }

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
