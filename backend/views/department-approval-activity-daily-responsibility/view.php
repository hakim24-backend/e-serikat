<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = 'Data Pertangungjawaban';
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="approve-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'activity.activityDailyBudgetSectionsOne.budget_value_sum',
                'label'=>'Uang Muka'
            ],
            [
                'attribute'=>'activity.activityDailyBudgetSectionsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan'
            ],
            [
                'attribute'=>'file',
                'format'=>'raw',
                'value'=> (($model->file != "" )? $model->file." <br>".Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) : "-"),
                'label'=>'File'
            ],
            [
                'attribute'=>'photo',
                'value'=>'../../web/template/'.$model->photo,
                'format' => ['image',['width'=>'100']],
                'label'=>'Foto'
            ],
            [
                'attribute'=>'activity.name_activity',
                'label'=>'Nama Kegiatan',
                
                
            ],
            [
                'attribute'=>'activity.title',
                'label'=>'Judul Kegiatan'
            ],
            [
                'attribute'=>'activity.background',
                'label'=>'Latar Belakang Kegiatan',
                
            ],
            [
                'attribute'=>'activity.purpose',
                'label'=>'Tujuan Kegiatan',
                
            ],
            [
                'attribute'=>'activity.target_activity',
                'label'=>'Target Kegiatan',
                
            ]
        ],
    ]) ?>
</div>
