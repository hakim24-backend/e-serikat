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
$Role = Yii::$app->user->identity->roleName();

$seksi = User::find()->where(['role'=>8])->all();
$array_seksi = ArrayHelper::map(User::find()->all(), 'id','name');
$list_seksi = array_values($array_seksi);
// $range = date('Y-m-d').' to '.date('Y-m-d');
// $range_start = date('Y-m-d');
// $range_end = date('Y-m-d');
?>

<div class="activity-form">

  <p>
      <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
  </p>


    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Kegiatan Uang Muka Rutin Departemen</h3>

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

                        <?= $baru->department_budget_value ?>

                    </div>
                </div>
            </div>
            <br>
            <br>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-4">Nilai Anggaran</label>
                    <div class="col-sm-8">
                        <?= $form->field($budget, 'budget_value_sum')->textInput(['class' => 'form-control', 'disabled'=>true] )->label(false); ?>
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
                <label>Judul</label>
              </div>
              <div class="col-md-10">
                <?= $model->title?>
              </div>
            </div>
            <br>
            <br>
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Deskripsi</label>
              </div>
              <div class="col-md-10">
                <?= $model->description ?>
              </div>
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
