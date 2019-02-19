<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use dosamigos\google\maps\services\DirectionsClient;
use common\models\User;
use yii\helpers\ArrayHelper;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\services\DirectionsWayPoint;
use dosamigos\google\maps\services\TravelMode;
use dosamigos\google\maps\overlays\PolylineOptions;
use dosamigos\google\maps\services\DirectionsRenderer;
use dosamigos\google\maps\services\DirectionsService;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\services\DirectionsRequest;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\layers\BicyclingLayer;
use wbraganca\dynamicform\DynamicFormWidget;
/* @var $this yii\web\View */

/* @var $model common\models\Activity */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Data Kegiatan';

$range = date('Y-m-d').' to '.date('Y-m-d');
$range_start = date('Y-m-d');
$range_end = date('Y-m-d');
$Role = Yii::$app->user->identity->roleName();


if($Role != "Sekretariat"){
  $minDate = date('Y-m-d',strtotime("-1 weeks"));
  $maxDate = date('Y-m-d',strtotime("+1 month"));
}else{
  $minDate = 0;
  $maxDate = 0;
}



$seksi = User::find()->where(['role'=>8])->all();
$array_seksi = ArrayHelper::map(User::find()->all(), 'id','name');
$list_seksi = array_values($array_seksi);
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

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
                      <label class="col-sm-4">Jenis SDM</label>
                      <div class="col-sm-8">
                          <?php if ($Role == "Sekretariat"){ ?>
                          <?= Html::dropDownList('jenis_sdm_source', null, [4 => 'Sekretariat'], ['prompt' => 'Pilih Jenis SDM', 'class'=>'col-sm-8', 'id'=>'jenis-asal']) ?>
                          <?php }else if($Role == "Seksi"){ ?>
                          <?= Html::dropDownList('jenis_sdm_source', null , [8 => 'Seksi'], ['prompt' => 'Pilih Jenis SDM', 'class'=>'col-sm-8', 'id'=>'jenis-asal']) ?>
                          <?php } ?>
                      </div>
                  </div>
              </div>
              <br>
              <br>
              <div class="col-sm-12">
                  <div class="form-group">
                      <label class="col-sm-4">Kode Anggaran</label>
                      <div class="col-sm-8">
                          <?= Html::dropDownList('source_sdm', null, [], ['prompt' => 'Pilih Kode Anggaran', 'class'=>'col-sm-8','id'=>'kode-asal']) ?>
                      </div>
                  </div>
              </div>
              <br>
              <br>
              <div id="nilai-anggaran-source">
              </div>
               <div class="col-sm-12">
                  <div class="form-group">
                      <label class="col-sm-4">Uang Muka Anggaran</label>
                      <div class="col-sm-8">
                          <?= Html::textInput('money_budget', '', ['autofocus' => true, 'required'=>true, 'type'=>'number', 'step'=>'any', 'min'=>0, 'class'=>'col-sm-8 uang-muka', 'id'=>'value-budget']) ?>
                      </div>
                  </div>
              </div>
              <br>
              <br>
              <div class="col-sm-12">
                  <div class="form-group">
                      <label class="col-sm-4">Nilai Anggaran</label>
                      <div class="col-sm-8">
                          <?= Html::textInput('source_value', '', ['autofocus' => true, 'required'=>true, 'type'=>'number', 'step'=>'any', 'min'=>0, 'class'=>'col-sm-8 nilai-anggaran', 'id'=>'value-budget']) ?>
                      </div>
                  </div>
              </div>
              <br>
              <br>
          </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
          <label>Isi informasi lengkap kegiatan</label>
        </div>
        <div class="box-body">
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Nama Kegiatan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'name_activity',['inputOptions'=>['autocomplete'=>'off']])->textInput(['maxlength' => true, 'required' => true])->label(false) ?>
              </div>
            </div>
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Judul/Tema</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'title',['inputOptions'=>['autocomplete'=>'off']])->textInput(['maxlength' => true, 'required' => true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Latar Belakang</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'background',['inputOptions'=>['autocomplete'=>'off']])->textarea(['rows' => 4, 'required' => true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Tujuan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'purpose',['inputOptions'=>['autocomplete'=>'off']])->textarea(['rows' => 4, 'required' => true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Sasaran Kegiatan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'target_activity',['inputOptions'=>['autocomplete'=>'off']])->textarea(['rows' => 4, 'required' => true])->label(false) ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Tempat Pelaksanaan</label>
              </div>
              <div class="col-md-10">
                <?=
                	$form->field($model, 'place_activity')->widget(\kalyabin\maplocation\SelectMapLocationWidget::className(), [
                	    'attributeLatitude' => 'place_activity_x',
                	    'attributeLongitude' => 'place_activity_y',
                	    'googleMapApiKey' => 'AIzaSyDEJifTz-2J9QyeCN9F45uNcSozkeLqSaI',
                	    'wrapperOptions' => ['style'=>'width: 100%; height: 200px;']
                	])->label(false);
                ?>
              </div>
            </div>
          </div>
        </div>
    </div>

<div class="box box-primary">
  <div class="box-header with-border">
    <label>Informasi Kepengurusan Utama</label>
  </div>
  <div class="box-body">
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Ketua</label>
            <div class="col-sm-8">
                <?= Html::dropDownList('ketua', null, ArrayHelper::map(User::find()->all(), 'name', 'name'), ['autofocus' => true, 'required'=>true,'class'=>'col-sm-8']) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Wakil</label>
            <div class="col-sm-8">
                <?= Html::dropDownList('wakil', null, ArrayHelper::map(User::find()->all(), 'name', 'name'), ['autofocus' => true, 'required'=>true,'class'=>'col-sm-8']) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Sekretaris</label>
            <div class="col-sm-8">
                <?= Html::dropDownList('sekretaris', null, ArrayHelper::map(User::find()->all(), 'name', 'name'), ['autofocus' => true, 'required'=>true,'class'=>'col-sm-8']) ?>            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Bendahara</label>
            <div class="col-sm-8">
                <?= Html::dropDownList('bendahara', null, ArrayHelper::map(User::find()->all(), 'name', 'name'), ['autofocus' => true, 'required'=>true,'class'=>'col-sm-8']) ?>            </div>
        </div>
    </div>
  </div>
</div>


<div class="box box-primary">
  <div class="box-header with-border">
    <label>Informasi Seksi</label>
  </div>
  <div class="box-body">
    <!-- main member -->

        <div class="panel panel-default">
            <div class="panel-heading"><h4><i class="glyphicon glyphicon-user"></i> Kepengurusan Seksi</h4></div>
            <div class="panel-body">
              <?php DynamicFormWidget::begin([

                      'widgetContainer' => 'dynamicform_wrapper',

                      'widgetBody' => '.container-items',

                      'widgetItem' => '.house-item',

                      'limit' => 10,

                      'min' => 1,

                      'uniqueClass'=>'form-control',

                      'autocompleteDatasource'=>$list_seksi,

                      'insertButton' => '.add-house',

                      'deleteButton' => '.remove-house',

                      'model' => $modelsSection[0],

                      'formId' => 'dynamic-form',

                      'formFields' => [

                          'section_name',

                      ],

                  ]); ?>

                  <table class="table table-bordered table-striped">

                      <thead>

                          <tr>

                              <th>Bagian Seksi</th>

                              <th style="width: 450px;">Anggota Seksi</th>

                              <th class="text-center" style="width: 90px;">

                                  <button type="button" class="add-house btn btn-success btn-xs"><span class="fa fa-plus"></span></button>

                              </th>

                          </tr>

                      </thead>

                      <tbody class="container-items">

                      <?php foreach ($modelsSection as $indexSection => $modelSection): ?>

                          <tr class="house-item">

                              <td class="vcenter">

                                  <?php

                                      // necessary for update action.

                                      if (! $modelSection->isNewRecord) {

                                          echo Html::activeHiddenInput($modelSection, "[{$indexSection}]id");

                                      }

                                  ?>

                                  <?= $form->field($modelSection, "[{$indexSection}]section_name",['inputOptions'=>['autocomplete'=>'off']])->label(false)->textInput(['maxlength' => true, 'required' => true]) ?>

                              </td>

                              <td>

                                  <?= $this->render('_form-members', [

                                      'form' => $form,

                                      'indexSection' => $indexSection,

                                      'modelsMember' => $modelsMember[$indexSection],

                                      'list_seksi' => $list_seksi,

                                  ]) ?>

                              </td>

                              <td class="text-center vcenter" style="width: 90px; verti">

                                  <button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>

                              </td>

                          </tr>

                       <?php endforeach; ?>

                      </tbody>

                  </table>

                  <?php DynamicFormWidget::end(); ?>
            </div>
        </div>
  </div>
</div>

<?php if($Role == "Sekretariat"){ ?>

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
                                      'format' => 'Y-m-d',
                                  ],
                                  'drops' => 'up',
                              ]
                          ]) . $addon;
                          echo '</div>';
                 ?>
                </div>
              </div>
          </div>
      </div>
          <br>



<?php }else{ ?>

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
                                      'format' => 'Y-m-d',
                                  ],
                                  'drops' => 'up',
                                  'minDate' => date('Y-m-d',strtotime("-4 days")),
                                  'maxDate' => date('Y-m-d',strtotime("+1 month")),
                              ]
                          ]) . $addon;
                          echo '</div>';
                 ?>
                </div>
              </div>
          </div>
      </div>
          <br>

<?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$url = Yii::$app->urlManager->createUrl('/kegiatan-rutin/kode-tujuan?id=');
$url2 = Yii::$app->urlManager->createUrl('/kegiatan-rutin/nilai-anggaran');
$url3 = Yii::$app->urlManager->createUrl('/kegiatan-rutin/check-anggaran?value=');

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

$('.nilai-anggaran').on('change',function(){
    var uangmuka = $('.uang-muka').val();
    var nilaisekarang = $('#nilai-sekarang').text();
    var nilaianggaran = $('.nilai-anggaran').val();
    var tipe = $('#jenis-asal').val();
    var kode = $('#kode-asal').val();

    var res = parseInt(nilaisekarang.replace("Rp.",""));
    if(parseInt(nilaianggaran) > res){
      alert('Nilai Anggaran Lebih Besar dari Nilai Anggaran Saat Ini. Mohon ubah nilai yang diinputkan !');
    }
    if(parseInt(nilaianggaran) < parseInt(uangmuka)){
      alert('Nilai Anggaran Lebih Kecil dari Uang Muka yang Diajukan. Mohon ubah nilai yang diinputkan !');
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
    }
    if(parseInt(uangmuka) > parseInt(nilaianggaran)){
      alert('Uang Muka Lebih Besar dari Anggaran Yang Diajukan. Mohon ubah nilai yang diinputkan !');
    }

});


$('#jenis-asal').on('change',function(){
    var tipe = $('#jenis-asal').val();
    $.ajax({
        url : "$url" + tipe,
        dataType : 'html',
        type : 'post',
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
