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
                                    'header' => 'Tanggal Mulai',
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
                                      'class' => 'yii\grid\ActionColumn',
                                      'contentOptions' => ['style' => 'width:160px;'],
                                      'header'=>'Actions',
                                      'template' => ' {apply} {view} {reject}',
                                      'buttons' => [
                                        'apply' => function($url, $model, $key)
                                        {
                                                if ($model->department_status) {
                                                    $url = Url::toRoute(['approval-department-activity-daily/update-apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Edit Data Kegiatan Rutin',
                                                        ]
                                                    );
                                                } else {
                                                    $url = Url::toRoute(['approval-department-activity-daily/apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-ok"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Apply Data Kegiatan Rutin',
                                                        ]
                                                    );
                                                }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['approval-department-activity-daily/view', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-eye-open"></span>',
                                                        $url,
                                                        [
                                                            'title' => 'View Data Kegiatan Rutin',
                                                        ]
                                                    );
                                        },
                                        'reject' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['approval-department-activity-daily/reject', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-remove"></span> |',
                                                        $url,
                                                        [
                                                            'title' => 'Reject Data Kegiatan Rutin',
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
