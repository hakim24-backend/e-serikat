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
<div class="department-index">

    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Buat Akun', ['create','id' => $id], ['class' => 'btn btn-success']) ?>   
    </p> 

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
            </div>
        </div>
    </div>
</div>
</div>
