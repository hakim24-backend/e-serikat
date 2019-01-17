<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Section';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Buat Akun', ['create','id' => $id], ['class' => 'btn btn-success']) ?>   
    </p> 

   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'section_name',
            // 'id_depart',
            // 'section_code',
            // 'status_budget',
            // 'user_id',

            [
            'header' => 'Nama Seksi',
            'attribute' => 'section_name',
            ],

            [
            'header' => 'ID Departemen',
            'attribute' => 'id_depart',
            ],

            [
            'header' => 'Kode Seksi',
            'attribute' => 'section_code',
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
                'template' => '| {update} | {view} | {delete} |',
                'buttons' => [
                    // 'edit' => function($url, $model, $key)
                    // {
                    //     if ($model->user) {
                    //         $url = Url::toRoute(['/department/create', 'id' => $model->id]);
                    //         return Html::a(
                    //             '<span class="glyphicon glyphicon-plus"></span>',
                    //             $url, 
                    //             [
                    //                 'title' => 'Create User',
                    //             ]
                    //         );
                    //     }
                    //     else{
                    //         $url = Url::toRoute(['/chief/create', 'id' => $model->id]);
                    //         return Html::a(
                    //             '<span class="glyphicon glyphicon-plus"></span>',
                    //             $url, 
                    //             [
                    //                 'title' => 'Buat User',
                    //             ]
                    //         );
                    //     }
                    // }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
