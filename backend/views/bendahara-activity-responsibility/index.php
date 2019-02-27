<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
$this->title = 'Data Pertanggung Jawaban Kegiatan Rutin';
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
                                        'style' => 'width:50%'
                                        ],
                                        'attribute' => 'title',
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
                                      'attribute' => 'Status',
                                      'filter' => false,
                                      'format' => 'raw',
                                       'value' => function ($model) {

                                         if($model[0]=="kegiatan"){
                                           $data = ActivityResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                           if($data->responsibility_value == 0)
                                           {
                                             return '<span class="label label-info">Belum Dikonfirmasi</span>';
                                           }
                                           else if($data->responsibility_value == 1)
                                           {
                                               return '<span class="label label-success">Diterima Kepala Departemen</span>';
                                           }
                                           else if($data->responsibility_value == '2')
                                           {
                                             return '<span class="label label-success">Diterima Ketua</span>';
                                           }
                                           else if($data->responsibility_value == '3')
                                           {
                                             return '<span class="label label-success">Selesai</span>';
                                           }
                                           // if($model->activityResponsibilities[0]['responsibility_value'] == '0')
                                           // {
                                           // }
                                           // else if($model->activityResponsibilities[0]['responsibility_value'] == '1')
                                           // {
                                           //   return '<span class="label label-success">Diterima Kepala Departemen</span>';
                                           // }
                                           // else if($model->activityResponsibilities[0]['responsibility_value'] == '2')
                                           // {
                                           //   return '<span class="label label-success">Diterima Ketua</span>';
                                           // }
                                           // else if($model->activityResponsibilities[0]['responsibility_value'] == '3')
                                           // {
                                           //   return '<span class="label label-success">Selesai</span>';
                                           // }
                                         }else if($model[0]=="rutin"){
                                           $data = ActivityDailyResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                           if($data->responsibility_value == 0)
                                           {
                                             return '<span class="label label-info">Belum Dikonfirmasi</span>';
                                           }
                                           else if($data->responsibility_value == 1)
                                           {
                                               return '<span class="label label-success">Diterima Kepala Departemen</span>';
                                           }
                                           else if($data->responsibility_value == '2')
                                           {
                                             return '<span class="label label-success">Diterima Ketua</span>';
                                           }
                                           else if($data->responsibility_value == '3')
                                           {
                                             return '<span class="label label-success">Selesai</span>';
                                           }
                                         }
                                       },
                                    ],


                                [

                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{closing} {view}',
                                'buttons' => [
                                        'closing' => function($url, $model, $key)
                                        {
                                          if($model[0]=="kegiatan"){
                                            $dataRespo = ActivityResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                            if($dataRespo->responsibility_value == 2){
                                              $url = Url::toRoute(['/bendahara-activity-responsibility/closing', 'id' => $model['id']]);
                                              return Html::a(
                                                '| <span class="glyphicon glyphicon-ok"></span> ',
                                                $url,
                                                [
                                                  'title' => 'Closing Pertanggungjawaban',
                                                ]
                                              );
                                            }
                                          }else if($model[0]=="rutin"){
                                            $dataRespo = ActivityDailyResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                            if($dataRespo->responsibility_value == 2){
                                              $url = Url::toRoute(['/bendahara-activity-daily-responsibility/closing', 'id' => $model['id']]);
                                              return Html::a(
                                                '| <span class="glyphicon glyphicon-ok"></span> ',
                                                $url,
                                                [
                                                  'title' => 'Closing Pertanggungjawaban',
                                                ]
                                              );
                                            }
                                          }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                          if($model[0]=="kegiatan"){
                                            $url = Url::toRoute(['/bendahara-activity-responsibility/view', 'id' => $model['id']]);
                                            return Html::a(
                                                '| <span class="glyphicon glyphicon-eye-open"></span> |',
                                                $url,
                                                [
                                                    'title' => 'Download Pertanggungjawaban',
                                                ]
                                            );
                                          }else if($model[0]=="rutin"){
                                            $url = Url::toRoute(['/bendahara-activity-daily-responsibility/view', 'id' => $model['id']]);
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
                                            $url = Url::toRoute(['/bendahara-activity-responsibility/report', 'id' => $model['id']]);
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
                                            $url = Url::toRoute(['/bendahara-activity-daily-responsibility/report', 'id' => $model['id']]);
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
