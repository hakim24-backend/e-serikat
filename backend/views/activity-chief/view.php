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
use kartik\money\MaskMoney;

/* @var $this yii\web\View */

/* @var $model common\models\Activity */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Data Kegiatan';
$Role = Yii::$app->user->identity->roleName();

$seksi = User::find()->where(['role'=>8])->all();
$array_seksi = ArrayHelper::map(User::find()->all(), 'id','name');
$list_seksi = array_values($array_seksi);
function to_rp($val)
{
    return "Rp " . number_format($val,0,',','.');
}
?>

<div class="activity-form">

  <p>
      <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
  </p>


    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Data Kegiatan Ketua</h3>

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

                        <?= to_rp($baru->chief_budget_value) ?>

                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4">Nilai Anggaran</label>
                    <div class="col-sm-8">
                        <?php
                          echo $form->field($budget, 'budget_value_sum')->widget(MaskMoney::classname(), [
                            'pluginOptions' => [
                              'prefix' => 'Rp ',
                              'thousands' => '.',
                              'decimal' => ',',
                              'precision' => 0
                            ],
                            'options' => [
                              'disabled' => true,
                            ]
                        ])->label(false);
                        ?>
                        <!-- <?= $form->field($budget, 'budget_value_sum')->textInput(['class' => 'form-control', 'disabled'=>true] )->label(false); ?> -->
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
                <?= $form->field($model, 'name_activity',['inputOptions'=>['autocomplete'=>'off']])->textInput(['maxlength' => true, 'required' => true, 'disabled'=>true])->label(false) ?>
              </div>
            </div>
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Judul</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'title',['inputOptions'=>['autocomplete'=>'off']])->textInput(['maxlength' => true, 'disabled'=>true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Latar Belakang</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'background',['inputOptions'=>['autocomplete'=>'off']])->textarea(['rows' => 4, 'disabled'=>true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Tujuan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'purpose',['inputOptions'=>['autocomplete'=>'off']])->textarea(['rows' => 4, 'disabled'=>true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Target Kegiatan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'target_activity',['inputOptions'=>['autocomplete'=>'off']])->textarea(['rows' => 4, 'disabled'=>true])->label(false) ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Lokasi</label>
              </div>
              <div class="col-md-10">
                <?= $model->place_activity;
                ?>
              </div>
            </div>
          </div>
        </div>
    </div>

<div class="box box-primary" id="box-pengurusan">
  <div class="box-header with-border">
    <label>Informasi Kepengurusan Utama</label>
  </div>
  <div class="box-body">
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Ketua</label>
            <div class="col-sm-8">
                <?= $ketua->name_member ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Wakil</label>
            <div class="col-sm-8">
              <?= $wakil->name_member ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Sekretaris</label>
            <div class="col-sm-8">
              <?= $sekretaris->name_member?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Bendahara</label>
            <div class="col-sm-8">
              <?= $bendahara->name_member?>
            </div>
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

                      'uniqueClass'=>'form-control-ui',

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

                                  <?= $form->field($modelSection, "[{$indexSection}]section_name",['inputOptions'=>['autocomplete'=>'off']])->label(false)->textInput(['maxlength' => true, 'disabled'=>true]) ?>

                              </td>

                              <td>

                                  <?= $this->render('_form-members-view', [

                                      'form' => $form,

                                      'indexSection' => $indexSection,

                                      'modelsMember' => $modelsMember[$indexSection],

                                      'list_seksi' => $list_seksi,

                                  ]) ?>

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
                                'disabled' => true,
                            ],
                            'pluginOptions'=>[
                                'locale'=>[
                                    'format' => 'Y-m-d',
                                ],
                                'drops' => 'up',
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
        <br>

    <?php ActiveForm::end(); ?>
</div>

<div class="box box-primary">
        <div class="box-header with-border">
          <label>Alasan Ditolak</label>
        </div>
        <div class="box-body">
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Keterangan</label>
              </div>
              <div class="col-md-10">
                <?php if ($reject == null) { ?>
                    <p>-</p>
                <?php } else { ?>
                    <?= $reject->message?>
                 <?php } ?>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  
<style>
#box-pengurusan .form-group{
    margin-bottom:45px !important;
}
</style>
