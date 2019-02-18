<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Uang Muka Kegiatan Rutin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <!-- <p>
        <?= Html::a('Input Data Uang Muka Kegiatan Rutin', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

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
                                //       'style'=>'width:15%'
                                //     ],
                                // 'attribute' => 'finance_status',
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
                                      'style' => 'width:70%'
                                    ],
                                'attribute' => 'description',
                                ],


                                [

                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{update} {download}',
                                'buttons' => [
                                        'update' => function($url, $model, $key)
                                        {
                                                if ($model->activityDailyResponsibilities) {
                                                    $url = Url::toRoute(['/department-activity-daily-responsibility/update', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Edit Pertanggungjawaban',
                                                        ]
                                                    );
                                                } else {
                                                    $url = Url::toRoute(['/department-activity-daily-responsibility/create', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-plus"></span> ',
                                                        $url,
                                                        [
                                                            'title' => 'Buat Pertanggungjawaban',
                                                        ]
                                                    );
                                                }
                                        },
                                        'download' => function($url, $model, $key)
                                        {
                                          if ($model->activityDailyResponsibilities) {

                                                    $url = Url::toRoute(['/department-activity-daily-responsibility/report', 'id' => $model->id]);
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
