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
<?php if ($role->role == 7) { ?>
<div class="approve-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'activity.activityDailyBudgetDepartsOne.budget_value_sum',
                'label'=>'Uang Muka'
            ],
            [
                'attribute'=>'activity.activityDailyBudgetDepartsOne.budget_value_dp',
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
                'attribute'=>'activity.title',
                'label'=>'Judul'
            ],
            [
                'attribute'=>'activity.description',
                'label'=>'Deskripsi'
            ],
        ],
    ]) ?>
</div>
<?php } elseif ($role->role == 8) { ?>
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
                'value'=>Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']),
                'label'=>'File'
            ],
            [
                'attribute'=>'photo',
                'value'=>'../../web/template/'.$model->photo,
                'format' => ['image',['width'=>'100']],
                'label'=>'Foto'
            ],
            [
                'attribute'=>'activity.title',
                'label'=>'Judul'
            ],
            [
                'attribute'=>'activity.description',
                'label'=>'Deskripsi'
            ],
        ],
    ]) ?>
</div>
<?php } ?>
