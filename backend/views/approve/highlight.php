<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Form Pertanggungjawaban';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-responsibility-index">

    <p>
        <?= Html::a('Buat Pertanggungjawaban', ['create','id' => $id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'description:ntext',
            // 'responsibility_value',
            // 'file:ntext',
            // 'photo:ntext',
            //'activity_id',

            [
            'header' => 'Deskripsi',
            'attribute' => 'description',
            ],

            // [
            // 'header' => 'File',
            // 'attribute' => 'file',
            // ],

            // [
            // 'header' => 'Foto',
            // 'attribute' => 'photo',
            // ],


            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '| {view} | {update} | {delete}',
            ],
        ],
    ]); ?>
</div>