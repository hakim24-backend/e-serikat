<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\mpdf\Pdf;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDaily */

$this->title = 'Data Kegiatan Rutin';
$this->params['breadcrumbs'][] = ['label' => 'Activity Dailies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Role = Yii::$app->user->identity->roleName();
?>
<div class="activity-daily-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Download File', ['report','id' => $model->id], ['class' => 'btn btn-success']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'activityDailyBudgetChiefsOne.budget_value_dp',
                'label'=>'Nilai Uang Muka Anggaran'
                
            ],
            [
                'attribute'=>'activityDailyBudgetChiefsOne.budget_value_sum',
                'label'=>'Nilai Uang Total Anggaran'
                
            ],
            [
                'attribute'=>'title',
                'label'=>'Judul'
            ],
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'date_start',
                'label'=>'Tanggal Mulai'
            ],
            [
                'attribute'=>'date_end',
                'label'=>'Tanggal Berakhir'
            ],
            [
                'attribute'=>'done',
                'label'=>'Status'
            ],
        ],
    ]) ?>

</div>