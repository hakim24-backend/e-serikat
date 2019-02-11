<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
                                'header' => 'Status Anggaran',
                                'attribute' => 'finance_status',
                                ],

                                [
                                'header' => 'Judul',
                                'attribute' => 'title',
                                ],

                                [
                                'header' => 'Latar Belakang',
                                'attribute' => 'background',
                                ],

                                [
                                'header' => 'Tujuan',
                                'attribute' => 'purpose',
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
                                'header' => 'Action',
                                'template' => '{closing} {view} {download}',
                                'buttons' => [
                                        'closing' => function($url, $model, $key)
                                        {
                                                // if ($model->activityDailyResponsibilities) {
                                                //     $url = Url::toRoute(['/activity-daily-responsibility/update', 'id' => $model->id]);
                                                //     return Html::a(
                                                //         '| <span class="glyphicon glyphicon-pencil"></span> ',
                                                //         $url, 
                                                //         [
                                                //             'title' => 'Edit Pertanggungjawaban',
                                                //         ]
                                                //     );
                                                // } else {
                                                    $url = Url::toRoute(['/bendahara-activity-responsibility/closing', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-ok"></span> ',
                                                        $url, 
                                                        [
                                                            'title' => 'Closing Pertanggungjawaban',
                                                        ]
                                                    );
                                                // }
                                        },
                                        'view' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['/bendahara-activity-responsibility/view', 'id' => $model->id]);
                                                    return Html::a(
                                                        '| <span class="glyphicon glyphicon-eye-open"></span> |',
                                                        $url, 
                                                        [
                                                            'title' => 'Download Pertanggungjawaban',
                                                            'data-pjax' => 0, 
                                                            'target' => '_blank'
                                                        ]
                                                    );
                                        },
                                        'download' => function($url, $model, $key)
                                        {
                                                    $url = Url::toRoute(['/bendahara-activity-responsibility/report', 'id' => $model->id]);
                                                    return Html::a(
                                                        ' <span class="glyphicon glyphicon-download"></span> |',
                                                        $url, 
                                                        [
                                                            'title' => 'Download Pertanggungjawaban',
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
