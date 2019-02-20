<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Approval - Data Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

      <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                            <?php Pjax::begin(); ?>
                              <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'options' =>[
                                  'style'=>'width:100%'
                                ],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                           [
                                        'header' => 'Judul',
                                        'headerOptions' =>[
                                        'style' => 'width:15%'
                                        ],
                                        'attribute' => 'title',
                                    ],

                                    [
                                        'header' => 'Tujuan',
                                        'headerOptions' =>[
                                        'style' => 'width:25%'
                                        ],
                                        'attribute' => 'purpose',
                                    ],

                                    [
                                        'header' => 'Tempat Pelaksanaan',
                                        'headerOptions' =>[
                                        'style' => 'width:20%'
                                        ],
                                        'attribute' => 'place_activity',
                                    ],

                                    [
                                        'header' => 'Tangal Mulai',
                                        'headerOptions' =>[
                                        'style' => 'width:15%'
                                        ],
                                        'attribute' => 'date_start',
                                    ],

                                    [
                                        'header' => 'Tanggal Berakhir',
                                        'headerOptions' =>[
                                        'style' => 'width:15%'
                                        ],
                                        'attribute' => 'date_end',
                                    ],


                                    [
                                        'attribute'=>'role',
                                        'header'=>'Role',
                                        'headerOptions' =>[
                                        'style' => 'width:20%'
                                        ],
                                        'format'=>'raw',
                                        'value' => function($model, $key, $index)
                                        {
                                            if($model->role == '1')
                                            {
                                                return '<span class="label label-info">Super Admin</span>';
                                            }
                                            else if($model->role == '2')
                                            {
                                                return '<span class="label label-info">Ketua Umum</span>';
                                            }
                                            else if($model->role == '3')
                                            {
                                                return '<span class="label label-info">Sekretaris Umum</span>';
                                            }
                                            else if($model->role == '4')
                                            {
                                                return '<span class="label label-info">Sekretariat</span>';
                                            }
                                            else if($model->role == '5')
                                            {
                                                return '<span class="label label-info">Bendahara</span>';
                                            }
                                            else if($model->role == '6')
                                            {
                                                return '<span class="label label-info">Ketua</span>';
                                            }
                                            else if($model->role == '7')
                                            {
                                                return '<span class="label label-info">Departemen</span>';
                                            }
                                            else if($model->role == '8')
                                            {
                                                return '<span class="label label-info">Seksi</span>';
                                            }
                                        },
                                    ],
                                    [
                                      'class' => 'yii\grid\ActionColumn',
                                      'contentOptions' => ['style' => 'width:160px;'],
                                      'header'=>'Actions',
                                      'template' => ' {apply} {view} {reject} ',
                                      'buttons' => [
                                        'apply' => function($url, $model, $key)
                                        {
                                                if ($model->finance_status ) {
                                                    $url = Url::toRoute(['bendahara/update-apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Ubah',
                                                        ]
                                                    );
                                                } else {
                                                    $url = Url::toRoute(['bendahara/apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-ok"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Setuju',
                                                        ]
                                                    );
                                                }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['bendahara/view', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-eye-open"></span>',
                                                        $url,
                                                        [
                                                            'title' => 'Lihat',
                                                        ]
                                                    );
                                        },
                                        'reject' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['bendahara/reject', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-remove"></span> |',
                                                        $url,
                                                        [
                                                            'title' => 'Tolak',
                                                        ]
                                                    );
                                        }
                                      ],
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
