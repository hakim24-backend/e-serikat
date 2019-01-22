<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Department';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <!-- <p>
        <?= Html::a('Buat Akun', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'depart_name',
            // 'id_chief',
            // 'depart_code',
            // 'status_budget',
            // 'user_id',

            [
            'header' => 'Nama Departemen',
            'attribute' => 'depart_name',
            ],

            [
            'header' => 'ID Ketua',
            'attribute' => 'id_chief',
            ],

            [
            'header' => 'Kode Departemen',
            'attribute' => 'depart_code',
            ],


            [
            'header' => 'Status Budget',
            'attribute' => 'status_budget',
            ],

            [
            'header' => 'Username ID',
            'attribute' => 'user_id',
            ],

            [   
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '| {highligt} |',
                'buttons' => [
                    

                    'highligt' => function($url, $model, $key)
                    {
                        if ($model->user) {
                            $url = Url::toRoute(['/section/highlight', 'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-th-list"></span>',
                                $url, 
                                [
                                    'title' => 'Creare Seksi',
                                ]
                            );
                        }
                    }
                ]
            ],
        ],
    ]); ?>
</div>
