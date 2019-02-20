<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Approval - Data Kegiatan Rutin';
$this->params['breadcrumbs'][] = $this->title;
$Role = Yii::$app->user->identity->roleName();
?>
<div class="activity-daily-reject-index">

    <?php Pjax::begin(); ?>
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

                                    //  [
                                    // 'header' => 'Status Anggaran',
                                    // 'headerOptions'=>[
                                    //   'style'=>'width:15%'
                                    // ],
                                    // 'attribute' => 'finance_status',
                                    // ],

                                    // [
                                    // 'header' => 'Status Departemen',
                                    // 'attribute' => 'department_status',
                                    // ],


                                    // [
                                    // 'header' => 'Status Ketua',
                                    // 'attribute' => 'chief_status',
                                    // ],

                                    // [
                                    // 'header' => 'Kode ID Ketua',
                                    // 'attribute' => 'chief_code_id',
                                    // ],

                                    // [
                                    // 'header' => 'Kode ID Departemen',
                                    // 'attribute' => 'department_code_id',
                                    // ],

                                    [
                                    'header' => 'Judul',
                                    'headerOptions' =>[
                                      'style' => 'width:20%'
                                    ],
                                    'attribute' => 'title',
                                    ],

                                    [
                                    'header' => 'Deskripsi',
                                    'headerOptions' =>[
                                      'style' => 'width:40%'
                                    ],
                                    'attribute' => 'description',
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
                                      'template' => ' {apply} {view} {reject}',
                                      'buttons' => [
                                        'apply' => function($url, $model, $key)
                                        {
                                                if ($model->finance_status) {
                                                    $url = Url::toRoute(['bendahara-rutin/update-apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Ubah',
                                                        ]
                                                    );
                                                } else {
                                                    $url = Url::toRoute(['bendahara-rutin/apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-ok"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Approve',
                                                        ]
                                                    );
                                                }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['bendahara-rutin/view', 'id' => $model->id]);
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
                                                    $url = Url::toRoute(['bendahara-rutin/reject', 'id' => $model->id]);
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
