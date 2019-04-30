<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
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

                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{create} {download}{view}',
                                'buttons' => [


                                        'create' => function($url, $model, $key)
                                        {
                                          if(Yii::$app->user->identity->role != 2 && Yii::$app->user->identity->role != 3){
                                            // if($model[0])
                                            // var_dump($model[0]);die;
                                            if($model[0]=="kegiatan"){
                                              $dataRespo = ActivityResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                              if($dataRespo){
                                                if($dataRespo->responsibility_value!=3){
                                                  $url = Url::toRoute(['/activity-responsibility/update', 'id' => $model['id']]);
                                                  return Html::a(
                                                    '| <span class="glyphicon glyphicon-pencil"></span> | ',
                                                    $url,
                                                    [
                                                      'title' => 'Update Laporan Pertanggung Jawaban',
                                                    ]
                                                  );
                                                }
                                              }else{
                                                $url = Url::toRoute(['/activity-responsibility/create', 'id' => $model['id']]);
                                                return Html::a(
                                                  '| <span class="glyphicon glyphicon-plus"></span> | ',
                                                  $url,
                                                  [
                                                    'title' => 'Create Laporan Pertanggung Jawaban',
                                                  ]
                                                );
                                              }
                                            }else if($model[0]=="rutin"){
                                              $dataRespo = ActivityDailyResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                              if($dataRespo){
                                                if($dataRespo->responsibility_value!=3){
                                                  $url = Url::toRoute(['/activity-daily-responsibility/update', 'id' => $model['id']]);
                                                  return Html::a(
                                                    '| <span class="glyphicon glyphicon-pencil"></span> | ',
                                                    $url,
                                                    [
                                                      'title' => 'Update Laporan Pertanggung Jawaban',
                                                    ]
                                                  );
                                                }
                                              }else{
                                                $url = Url::toRoute(['/activity-daily-responsibility/create', 'id' => $model['id']]);
                                                return Html::a(
                                                  '| <span class="glyphicon glyphicon-plus"></span> | ',
                                                  $url,
                                                  [
                                                    'title' => 'Create Laporan Pertanggung Jawaban',
                                                  ]
                                                );
                                              }
                                            }
                                          }
                                        },
                                        // die();
                                        'download' => function($url, $model, $key)
                                        {
                                          if($model[0]=="kegiatan"){
                                            $dataRespo = ActivityResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                            if($dataRespo){

                                              $url = Url::toRoute(['/activity-responsibility/report', 'id' => $model['id']]);
                                              return Html::a(
                                                '<span class="glyphicon glyphicon-download"></span> |',
                                                $url,
                                                [
                                                  'title' => 'Download Pertanggungjawaban',
                                                  'data-pjax' => 0,
                                                  'target' => '_blank'
                                                ]
                                              );
                                            }
                                          }else if($model[0]=="rutin"){
                                            $dataRespo = ActivityDailyResponsibility::find()->where(['activity_id'=>$model['id']])->one();
                                            if($dataRespo){
                                              $url = Url::toRoute(['/activity-daily-responsibility/report', 'id' => $model['id']]);
                                              return Html::a(
                                                '<span class="glyphicon glyphicon-download"></span> |',
                                                $url,
                                                [
                                                  'title' => 'Download Pertanggungjawaban',
                                                  'data-pjax' => 0,
                                                  'target' => '_blank'
                                                ]
                                              );

                                            }
                                          }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                          if($model[0]=="kegiatan"){
                                           
                                              $url = Url::toRoute(['/kegiatan/view', 'id' => $model['id']]);
                                              return Html::a(
                                                '  <span class="fa fa-eye"></span> |',
                                                $url,
                                                [
                                                  'title' => 'Download Pertanggungjawaban',
                                                  'data-pjax' => 0,
                                                 // 'target' => '_blank'
                                                ]
                                              );
                                            
                                          }else if($model[0]=="rutin"){
                                           
                                              $url = Url::toRoute(['/kegiatan-rutin/view', 'id' => $model['id']]);
                                              return Html::a(
                                                ' <span class="fa fa-eye"></span> |',
                                                $url,
                                                [
                                                  'title' => 'Download Pertanggungjawaban',
                                                  'data-pjax' => 0,
                                                 // 'target' => '_blank'
                                                ]
                                              );

                                            
                                          }
                                        }
                                    ]

                                ],

                            ],
                            // die()
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
