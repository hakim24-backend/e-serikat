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
// $range = date('Y-m-d').' to '.date('Y-m-d');
// $range_start = date('Y-m-d');
// $range_end = date('Y-m-d');
?>

<div class="activity-form">
    <div class="box box-primary">
        <div class="box-header with-border">
          <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
          <label>Isi informasi lengkap kegiatan</label>
        </div>
        <div class="box-body">
          <div class="form-group">
            <div class="col-md-12">
              <div class="col-md-2">
                <label>Nilai Anggaran</label>
              </div>
              <div class="col-md-10">
                <?= $form->field($model, 'section_budget_value')->widget(MaskMoney::classname(), [
                    'options' => [
                        'required'=>true,
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
                ]) ?>
              </div>
            </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<style>
#box-pengurusan .form-group{
    margin-bottom:45px !important;
}
</style>
