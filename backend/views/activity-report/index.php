<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
use Mpdf\Mpdf;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan - Data Kegiatan';
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn',
    'options' =>[
      'style'=>'width:100%'],
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
    ['class' => 'kartik\grid\ActionColumn', 'urlCreator'=>function(){return '#';}]
];
?>
<div class="activity-daily-index">
<br>
<div>
    <?= Html::input('text') ?>&nbsp;&nbsp;&nbsp;
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>
<br>

<?=
ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'exportConfig' => [
        ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_EXCEL => false,
        ExportMenu::FORMAT_PDF => [
            'pdfConfig' => [
                'methods' => [
                    'SetTitle' => 'Grid Export - Krajee.com',
                    'SetSubject' => 'Generating PDF files via yii2-export extension has never been easy',
                    'SetHeader' => ['Data Kegiatan  ||Dicetak Pada : ' . date("r")],
                    'SetFooter' => ['|Page {PAGENO}|'],
                    'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, GridView, Grid, yii2-grid, yii2-mpdf, yii2-export',
                ]
            ]
        ],
    ],
    'dropdownOptions' => [
        'label' => 'Export Data Kegiatan',
        'class' => 'btn btn-secondary'
    ]
])
?>
<br>
<br>

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

                                // [

                                // 'class' => 'yii\grid\ActionColumn',
                                // 'header' => 'Action',
                                // 'template' => '{closing} {view} {download}',
                                // 'buttons' => [
                                //         'closing' => function($url, $model, $key)
                                //         {
                                //                 // if ($model->activityDailyResponsibilities) {
                                //                 //     $url = Url::toRoute(['/activity-daily-responsibility/update', 'id' => $model->id]);
                                //                 //     return Html::a(
                                //                 //         '| <span class="glyphicon glyphicon-pencil"></span> ',
                                //                 //         $url, 
                                //                 //         [
                                //                 //             'title' => 'Edit Pertanggungjawaban',
                                //                 //         ]
                                //                 //     );
                                //                 // } else {
                                //                     $url = Url::toRoute(['/bendahara-activity-responsibility/closing', 'id' => $model->id]);
                                //                     return Html::a(
                                //                         '| <span class="glyphicon glyphicon-ok"></span> ',
                                //                         $url, 
                                //                         [
                                //                             'title' => 'Closing Pertanggungjawaban',
                                //                         ]
                                //                     );
                                //                 // }
                                //         },
                                //         'view' => function($url, $model, $key)
                                //         {
                                //                     $url = Url::toRoute(['/bendahara-activity-responsibility/view', 'id' => $model->id]);
                                //                     return Html::a(
                                //                         '| <span class="glyphicon glyphicon-eye-open"></span> |',
                                //                         $url, 
                                //                         [
                                //                             'title' => 'Download Pertanggungjawaban',
                                //                         ]
                                //                     );
                                //         },
                                //         'download' => function($url, $model, $key)
                                //         {
                                //                     $url = Url::toRoute(['/bendahara-activity-responsibility/report', 'id' => $model->id]);
                                //                     return Html::a(
                                //                         ' <span class="glyphicon glyphicon-download"></span> |',
                                //                         $url, 
                                //                         [
                                //                             'title' => 'Download Pertanggungjawaban',
                                //                             'data-pjax' => 0, 
                                //                             'target' => '_blank'
                                //                         ]
                                //                     );
                                //         },
                                //     ]

                                // ],
                            ],
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
