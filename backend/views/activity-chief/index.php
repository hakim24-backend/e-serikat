<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?=Html::encode($this->title)?></h1> -->
<?php
if (Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3') {?>
      <p>
          <?=Html::a('Input Data Uang Muka Kegiatan', ['create'], ['class' => 'btn btn-success'])?>
      </p>
        <?php }
?>

      <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                            <?php Pjax::begin();?>
                              <?=GridView::widget([
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
                                        'contentOptions' => ['style' => 'width:160px;'],
                                        'header' => 'Actions',
                                        'template' => ' {update} {view} {download}',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                if (Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3') {
                                                    return Html::a('| <span class="fa fa-pencil"></span>', $url, [
                                                        'title' => Yii::t('app', 'update'),
                                                    ]);
                                                }
                                            },
                                            'view' => function ($url, $model) {
                                                if (Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3') {
                                                    return Html::a('| <span class="fa fa-eye"></span>', $url, [
                                                        'title' => Yii::t('app', 'view'),
                                                    ]);
                                                }
                                            },
                                            'download' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('| <span class="fa fa-download"></span> |', $url, [
                                                          'title' => Yii::t('app', 'download'),
                                                          'data-pjax' => 0, 
                                                          'target' => '_blank'
                                              ]);
                                            }
                                          },
                                        ],

                                        'urlCreator' => function ($action, $model, $key, $index) {
                                            if ($action === 'update') {
                                                $url = Url::to(['activity-chief/update', 'id' => $model['id']]);
                                                return $url;
                                            } else if ($action === 'view') {
                                                $url = Url::to(['activity-chief/view', 'id' => $model['id']]);
                                                return $url;
                                            } else if ($action === 'download') {
                                              $url = Url::to(['activity-chief/report','id'=>$model['id']]);
                                              return $url;
                                          }
                                        },
                                    ],
                                ],
                            ]);?>
                        <?php Pjax::end();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
