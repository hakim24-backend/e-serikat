<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Pertanggungjawaban Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

<!--     <p>
        <?= Html::a('Input Data Pertanggung', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
 -->
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

                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{closing} {view} {download}',
                                'buttons' => [
                                        'closing' => function($url, $model, $key)
                                        {
                                          if($model[0]=="kegiatan"){
                                            $url = Url::toRoute(['/chief-approval-activity-responsibility/closing', 'id' => $model['id']]);
                                            return Html::a(
                                              '| <span class="glyphicon glyphicon-ok"></span> ',
                                              $url,
                                              [
                                                'title' => 'Closing Pertanggungjawaban',
                                              ]
                                            );
                                          }else if($model[0]=="rutin"){
                                            $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/closing', 'id' => $model['id']]);
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
                                          if($model[0]=="kegiatan"){
                                            $url = Url::toRoute(['/chief-approval-activity-responsibility/view', 'id' => $model['id']]);
                                            return Html::a(
                                              '| <span class="glyphicon glyphicon-eye-open"></span> |',
                                              $url,
                                              [
                                                'title' => 'Download Pertanggungjawaban',
                                              ]
                                            );
                                          }else if($model[0]=="rutin"){
                                            $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/view', 'id' => $model['id']]);
                                            return Html::a(
                                              '| <span class="glyphicon glyphicon-eye-open"></span> |',
                                              $url,
                                              [
                                                'title' => 'Download Pertanggungjawaban',
                                              ]
                                            );
                                          }
                                        },
                                        'download' => function($url, $model, $key)
                                        {
                                          if($model[0]=="kegiatan"){
                                            $url = Url::toRoute(['/chief-approval-activity-responsibility/report', 'id' => $model['id']]);
                                            return Html::a(
                                              ' <span class="glyphicon glyphicon-download"></span> |',
                                              $url,
                                              [
                                                'title' => 'Download Pertanggungjawaban',
                                                'data-pjax' => 0,
                                                'target' => '_blank'
                                              ]
                                            );

                                          }else if($model[0]=="rutin"){
                                            $url = Url::toRoute(['/chief-approval-activity-daily-responsibility/report', 'id' => $model['id']]);
                                            return Html::a(
                                              ' <span class="glyphicon glyphicon-download"></span> |',
                                              $url,
                                              [
                                                'title' => 'Download Pertanggungjawaban',
                                                'data-pjax' => 0,
                                                'target' => '_blank'
                                              ]
                                            );
                                          }
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
