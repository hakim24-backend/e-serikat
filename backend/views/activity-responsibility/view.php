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
            // 'id',
            // 'description',
            // 'responsibility_value',
            // 'file',
            // 'photo',
            // 'activity_id',

            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'responsibility_value',
                'label'=>'Nilai Pertangungjawaban'
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
        ],
    ]) ?>
</div>
