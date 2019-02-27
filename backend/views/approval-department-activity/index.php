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

                                    //  [
                                    // 'header' => 'Status Anggaran',
                                    // 'attribute' => 'finance_status',
                                    // ],
                                    [
                                    'header' => 'Judul',
                                    'headerOptions' =>[
                                      'style' => 'width:20%'
                                    ],
                                    'attribute' => 'title',
                                    ],

                                    [
                                    'header' => 'Latar Belakang',
                                    'headerOptions' =>[
                                      'style' => 'width:20%'
                                    ],
                                    'attribute' => 'background',
                                    ],

                                    [
                                    'header' => 'Tujuan',
                                    'headerOptions' =>[
                                      'style' => 'width:20%'
                                    ],
                                    'attribute' => 'purpose',
                                    ],

                                    [
                                    'header' => 'Tanggal Mulai',
                                    'attribute' => 'date_start',
                                    ],

                                    [
                                    'header' => 'Tanggal Berakhir',
                                    'attribute' => 'date_end',
                                    ],


                                    [
                                      'class' => 'yii\grid\ActionColumn',
                                      'contentOptions' => ['style' => 'width:160px;'],
                                      'header'=>'Actions',
                                      'template' => ' {apply} {view} {reject} ',
                                      'buttons' => [
                                        'apply' => function($url, $model, $key)
                                        {
                                                if ($model->department_status) {
                                                    $url = Url::toRoute(['approval-department-activity/update-apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Edit Pertanggungjawaban',
                                                        ]
                                                    );
                                                } else {
                                                    $url = Url::toRoute(['approval-department-activity/apply', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-ok"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Apply Pertanggungjawaban',
                                                        ]
                                                    );
                                                }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['approval-department-activity/view', 'id' => $model->id]);
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
                                                    $url = Url::toRoute(['approval-department-activity/reject', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-remove"></span> |',
                                                        $url,
                                                        [
                                                            'title' => 'Download Pertanggungjawaban',
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
