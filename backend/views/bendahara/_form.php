<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDailyReject */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Bendahara - Kegiatan Rutin';
?>

<div class="activity-daily-reject-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6])->label('Alasan Ditolak'); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
