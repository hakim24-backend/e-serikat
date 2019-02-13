<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDailyReject */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Data Kegiatan Rutin Sekretariat';
?>

<div class="activity-daily-reject-form">

    <?php $form = ActiveForm::begin(); ?>

<!--     <?= $form->field($model, 'activity_id')->textInput() ?> -->

    <?= $form->field($model, 'message')->textarea(['rows' => 6])->label('Alasan Ditolak') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
