<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use dosamigos\google\maps\services\DirectionsClient;

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
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
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
                <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Latar Belakang</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'background')->textarea(['rows' => 4])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Tujuan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'purpose')->textarea(['rows' => 4])->label(false) ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Target Kegiatan</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'target_activity')->textarea(['rows' => 4])->label(false) ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Lokasi</label>
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
                <?= Html::textInput('ketua', '', ['autofocus' => true, 'required'=>true, 'type'=>'text','class'=>'col-sm-8', 'id'=>'judul']) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Wakil</label>
            <div class="col-sm-8">
                <?= Html::textInput('wakil', '', ['autofocus' => true, 'required'=>false, 'type'=>'text','class'=>'col-sm-8', 'id'=>'judul']) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Sekretaris</label>
            <div class="col-sm-8">
                <?= Html::textInput('sekretaris', '', ['autofocus' => true, 'required'=>false, 'type'=>'text','class'=>'col-sm-8', 'id'=>'judul']) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="col-sm-4">Bendahara</label>
            <div class="col-sm-8">
                <?= Html::textInput('bendahara', '', ['autofocus' => true, 'required'=>false, 'type'=>'text','class'=>'col-sm-8', 'id'=>'judul']) ?>
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
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 4, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $modelsSection[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'section_name',
                    ],
                ]); ?>

                <div class="container-items"><!-- widgetContainer -->
                <?php foreach ($modelsSection as $i => $modelSection): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-left">Seksi <?php echo $i+1; ?></h3>
                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                                // necessary for update action.
                                if (! $modelSection->isNewRecord) {
                                    echo Html::activeHiddenInput($modelSection, "[{$i}]id");
                                }
                            ?>
                            <div class="row">
                              <div class="col-sm-6">

                                <div class="col-sm-12">
                                  <?= $form->field($modelSection, "[{$i}]section_name")->textInput(['maxlength' => true])->label('Nama Komite') ?>
                                </div>
                              </div>
                            </div><!-- .row -->
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
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


    <div class="form-group">

        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>
</div>
