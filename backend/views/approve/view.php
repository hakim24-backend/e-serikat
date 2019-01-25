<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="approve-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
                'format' => ['image',['width'=>'100','height'=>'100']],
                'label'=>'Foto'
            ],
        ],
    ]) ?>
</div>
