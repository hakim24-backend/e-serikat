<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Uang Muka Kegiatan Rutin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <!-- <p>
        <?= Html::a('Input Data Uang Muka Kegiatan Rutin', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

      <?php Pjax::begin(); ?>
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'finance_status',
            // 'department_status',
            // 'chief_status',
            // 'chief_code_id',
            // 'department_code_id',
            // 'title',
            // 'description:ntext',
            // 'role',
            // 'date',
            // 'done',

             [
            'header' => 'Status Anggaran',
            'attribute' => 'finance_status',
            ],

            [
            'header' => 'Status Departemen',
            'attribute' => 'department_status',
            ],


            [
            'header' => 'Status Ketua',
            'attribute' => 'chief_status',
            ],

            [
            'header' => 'Kode ID Ketua',
            'attribute' => 'chief_code_id',
            ],

            [
            'header' => 'Kode ID Departemen',
            'attribute' => 'department_code_id',
            ],

            [
            'header' => 'Judul',
            'attribute' => 'title',
            ],

            [
            'header' => 'Deskripsi',
            'attribute' => 'description',
            ],


            [

            'class' => 'yii\grid\ActionColumn',
            'header' => 'Action',
            'template' => '| {highligt} |',
            'buttons' => [
                    

                    'highligt' => function($url, $model, $key)
                    {
                        // if ($model->user) {
                            $url = Url::toRoute(['/approve/highlight', 'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-th-list"></span>',
                                $url, 
                                [
                                    'title' => 'Create Laporan Pertanggung Jawaban',
                                ]
                            );
                        // }
                        // else{
                        //     $url = Url::toRoute(['/department/view', 'id' => $model->id]);
                        //     return Html::a(
                        //         '<span class="glyphicon glyphicon-plus"></span>',
                        //         $url, 
                        //         [
                        //             'title' => 'View Data',
                        //         ]
                        //     );
                        // }
                    }
                ]
            
            ],

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
