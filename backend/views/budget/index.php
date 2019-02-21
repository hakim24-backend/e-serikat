<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sumber Dana';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-index">
  <?php
      if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){ ?>
    <p>
        <?= Html::a('Input Budget', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    // 'budget_code',
                                    // 'budget_year',
                                    // 'budget_name',
                                    // 'budget_value',

                                    [
                                    'header' => 'Kode Budget',
                                    'attribute' => 'budget_code',
                                    ],

                                    [
                                    'header' => 'Tahun Budget',
                                    'attribute' => 'budget_year',
                                    ],


                                    [
                                    'header' => 'Nama Budget',
                                    'attribute' => 'budget_name',
                                    ],

                                    [
                                    'header' => 'Nilai Saldo',
                                    'attribute' => 'budget_value',
                                    ],


                                    [
                                      'class' => 'yii\grid\ActionColumn',
                                      'contentOptions' => ['style' => 'width:160px;'],
                                      'header'=>'Actions',
                                      'template' => ' {update} {view} {delete} ',
                                      'buttons' => [
                                          'update' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('| <span class="fa fa-pencil"></span>', $url, [
                                                          'title' => Yii::t('app', 'update'),
                                              ]);
                                            }
                                          },
                                          'view' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('| <span class="fa fa-eye"></span>', $url, [
                                                          'title' => Yii::t('app', 'view'),
                                              ]);
                                            }
                                          },
                                          'delete' => function ($url, $model) {
                                            if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){
                                              return Html::a('| <span class="fa fa-trash"></span>', $url, [
                                                          'title' => Yii::t('app', 'Delete'),
                                                          'data-confirm' => Yii::t('yii', 'Are you sure you want to delete?'),
                                                          'data-method' => 'post', 'data-pjax' => '0',

                                              ]);
                                            }

                                          },
                                      ],

                                      'urlCreator' => function ($action, $model, $key, $index) {
                                          if ($action === 'update') {
                                              $url = Url::to(['budget/update','id'=>$model['id']]);
                                              return $url;
                                          }else if ($action === 'view') {
                                              $url = Url::to(['budget/view','id'=>$model['id']]);
                                              return $url;
                                          }else if ($action === 'delete') {
                                              $url = Url::to(['budget/delete','id'=>$model['id']]);
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
