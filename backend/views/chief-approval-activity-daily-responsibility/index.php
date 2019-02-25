<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Pertanggungjawaban Kegiatan Rutin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

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
                                      'style' => 'width:35%'
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

                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{closing} {view} {download}',
                                'buttons' => [
                                        'closing' => function($url, $model, $key)
                                        {
                                                if ($model->done) {
                                                    $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/update-closing', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                        $url, 
                                                        [
                                                            'title' => 'Edit Pertanggungjawaban',
                                                        ]
                                                    );
                                                } else {
                                                    $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/closing', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-ok"></span> ',
                                                        $url, 
                                                        [
                                                            'title' => 'Closing Pertanggungjawaban',
                                                        ]
                                                    );
                                                }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/view', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-eye-open"></span> |',
                                                        $url, 
                                                        [
                                                            'title' => 'View Pertanggungjawaban',
                                                        ]
                                                    );
                                        },
                                        'download' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/report', 'id' => $model->id]);
                                                    return Html::a(
                                                        '<span class="glyphicon glyphicon-download"></span> |',
                                                        $url, 
                                                        [
                                                            'title' => 'Download Pertanggungjawaban',
                                                            'data-pjax' => 0, 
                                                            'target' => '_blank'
                                                        ]
                                                    );
                                        },
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
