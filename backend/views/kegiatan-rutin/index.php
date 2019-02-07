<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$Role = Yii::$app->user->identity->roleName();
$this->title = 'Uang Muka Kegiatan Rutin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->
<?php
    if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){ ?>
      <p>
        <?= Html::a('Input Data Uang Muka Kegiatan Rutin', ['create'], ['class' => 'btn btn-success']) ?>
      </p>
    <?php } ?>
    
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
                                      'class' => 'yii\grid\ActionColumn',
                                      'contentOptions' => ['style' => 'width:160px;'],
                                      'header'=>'Actions',
                                      'template' => ' {update} {view} {download} ',
                                      'buttons' => [
                                          'update' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('| <span class="fa fa-pencil"></span> ', $url, [
                                                          'title' => Yii::t('app', 'update'),
                                              ]);
                                            }
                                          },
                                          'view' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('| <span class="fa fa-eye"></span> | ', $url, [
                                                          'title' => Yii::t('app', 'view'),
                                              ]);
                                            }
                                          },
                                          'download' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a(' <span class="fa fa-download"></span> | ', $url, [
                                                          'title' => Yii::t('app', 'view'),
                                                          'data-pjax' => 0, 
                                                          'target' => '_blank'
                                              ]);
                                            }
                                          }
                                      ],

                                      'urlCreator' => function ($action, $model, $key, $index) {
                                          if ($action === 'update') {
                                              $url = Url::to(['kegiatan-rutin/update','id'=>$model['id']]);
                                              return $url;
                                          }else if ($action === 'view') {
                                              $url = Url::to(['kegiatan-rutin/view','id'=>$model['id']]);
                                              return $url;
                                          } else if ($action === 'download') {
                                              $url = Url::to(['kegiatan-rutin/report','id'=>$model['id']]);
                                              return $url;
                                          }
                                       }
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
