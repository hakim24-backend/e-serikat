<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ketua';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    
   <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                        <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // 'id',
                            // 'chief_name',
                            // 'chief_code',
                            // 'status_budget',
                            // 'user_id',

                            [
                            'header' => 'Nama Ketua',
                            'attribute' => 'chief_name',
                            ],

                            [
                            'header' => 'Kode Ketua',
                            'attribute' => 'chief_code',
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
                                            $url = Url::toRoute(['/department/highlight', 'id' => $model->id]);
                                            return Html::a(
                                                '<span class="glyphicon glyphicon-th-list"></span>',
                                                $url, 
                                                [
                                                    'title' => 'Create Department ',
                                                ]
                                            );
                                        }
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
                </div>
            </div>
        </div>
    </div>
</div>
</div>
