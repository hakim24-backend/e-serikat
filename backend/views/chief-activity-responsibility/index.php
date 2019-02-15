<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ketua - Data Pertanggung Jawaban';
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
                                      'style' => 'width:30%'
                                ],
                                'attribute' => 'title',
                                ],
                                [
                                'header' => 'Latar Belakang',
                                'headerOptions' =>[
                                      'style' => 'width:30%'
                                ],
                                'attribute' => 'background',
                                ],
                                [
                                'header' => 'Tujuan',
                                'headerOptions' =>[
                                      'style' => 'width:30%'
                                ],
                                'attribute' => 'purpose',
                                ],
                                [

                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{create} {download} {delete}',
                                'buttons' => [


                                        'create' => function($url, $model, $key)
                                        {
                                          if(Yii::$app->user->identity->role != 2 && Yii::$app->user->identity->role != 3){
                                            if($model->activityResponsibilities){
                                              $url = Url::toRoute(['/chief-activity-responsibility/update', 'id' => $model->id]);
                                              return Html::a(
                                                '| <span class="glyphicon glyphicon-pencil"></span>',
                                                $url,
                                                [
                                                  'title' => 'Update Laporan Pertanggung Jawaban',
                                                ]
                                              );
                                            }else{
                                              $url = Url::toRoute(['/chief-activity-responsibility/create', 'id' => $model->id]);
                                              return Html::a(
                                                '| <span class="glyphicon glyphicon-plus"></span>',
                                                $url,
                                                [
                                                  'title' => 'Create Laporan Pertanggung Jawaban',
                                                ]
                                              );
                                            }
                                          }
                                        },
                                        'download' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['/chief-activity-responsibility/report', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-download"></span> |',
                                                        $url, 
                                                        [
                                                            'title' => 'Download Pertanggungjawaban',
                                                            'data-pjax' => 0, 
                                                            'target' => '_blank'
                                                        ]
                                                    );
                                        }
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
