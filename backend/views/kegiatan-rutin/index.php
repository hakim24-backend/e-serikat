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

    <?php }
 ?>

      <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                            <?php Pjax::begin(); ?>
                              <?= GridView::widget([
                                'dataProvider' => $dataProvider,
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
                                    'header' => 'Status Anggaran',
                                    'attribute' => 'finance_status',
                                    ],

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
                                    'attribute' => 'title',
                                    ],

                                    [
                                    'header' => 'Deskripsi',
                                    'attribute' => 'description',
                                    ],

                                    [
                                    'header' => 'Tangal Mulai',
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
                                      'template' => ' {update} {view} {delete} ',
                                      'buttons' => [
                                          'update' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('|<span class="fa fa-pencil"></span>', $url, [
                                                          'title' => Yii::t('app', 'update'),
                                              ]);
                                            }
                                          },
                                          'view' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('|<span class="fa fa-eye"></span>', $url, [
                                                          'title' => Yii::t('app', 'view'),
                                              ]);
                                            }
                                          },
                                          'delete' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('|<span class="fa fa-trash"></span>', $url, [
                                                          'title' => Yii::t('app', 'Delete'),
                                                          'data-confirm' => Yii::t('yii', 'Are you sure you want to delete?'),
                                                          'data-method' => 'post', 'data-pjax' => '0',

                                              ]);
                                            }

                                          },
                                      ],

                                      'urlCreator' => function ($action, $model, $key, $index) {
                                          if ($action === 'update') {
                                              $url = Url::to(['user/update','id'=>$model['id']]);
                                              return $url;
                                          }else if ($action === 'view') {
                                              $url = Url::to(['user/view','id'=>$model['id']]);
                                              return $url;
                                          }else if ($action === 'delete') {
                                              $url = Url::to(['user/delete','id'=>$model['id']]);
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
