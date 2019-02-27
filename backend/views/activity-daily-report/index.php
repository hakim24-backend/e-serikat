<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
use Mpdf\Mpdf;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Laporan - Data Kegiatan Rutin';
$this->params['breadcrumbs'][] = $this->title;
$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn',
    'options' =>[
      'style'=>'width:100%'],
    ],
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
      'style' => 'width:35%'
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
    'header' => 'Uang Muka',
    'headerOptions' =>[
        'style' => 'width:15%'
    ],
    'format'=> 'raw',
    'value' => function($data)
        {
            if($data->activityDailyBudgetChiefsOne != null){
                return $data->activityDailyBudgetChiefsOne->budget_value_sum;
            }elseif ($data->activityDailyBudgetDepartsOne != null) {
                return $data->activityDailyBudgetDepartsOne->budget_value_sum;
            }elseif($data->activityDailyBudgetSectionsOne != null){
                return $data->activityDailyBudgetSectionsOne->budget_value_sum;
            }else{
                return "-";
            }
        }
    ],

    ['class' => 'kartik\grid\ActionColumn', 'urlCreator'=>function(){return '#';}]
];

$range = date('Y-m-d').' to '.date('Y-m-d');
$range_start = date('Y-m-d');
$range_end = date('Y-m-d');
?>
<div class="activity-daily-index">
        <div class="box box-primary">

        <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'method'=>'get']); ?>
          <div class="box-header with-border">
            <label>Filter Data Kegiatan</label>
          </div>
          <div class="box-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="row">
                                <div class="col-md-3 col-xs-12">
                                    <label>Pilih SDM Data Kegiatan Rutin</label>
                                    <?php
                                        echo Html::dropDownList('jenis_sdm_source', 0, [6 => 'Ketua', 7 => 'Department', 8 => 'Seksi'], [
                                            'prompt' => '- Pilih SDM -',
                                            'class' => 'select2_single form-control',
                                            'id' => 'jenis-asal',
                                            'style' => 'width: 100%;',
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <label>Range Tanggal</label>
                                    <?php
                                    $addon = <<< HTML
HTML;
                                    echo '<div class="input-group drp-container">';
                                    echo DateRangePicker::widget([
                                        'name'=>'date_range',
                                        'value'=>$range,
                                        'useWithAddon'=>true,
                                        'convertFormat'=>true,
                                        'startAttribute' => 'from_date',
                                        'endAttribute' => 'to_date',
                                        'startInputOptions' => ['value' => $range_start],
                                        'endInputOptions' => ['value' => $range_end],
                                        'options' => [
                                            'class' => 'form-control',
                                        ],
                                        'pluginOptions'=>[
                                            'locale'=>[
                                                'format' => 'Y-m-d',
                                            ],
                                            'drops' => 'down',
                                        ]
                                    ]) . $addon;
                                    echo '</div>';
                                    ?>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label>&nbsp;</label>
                                    <br />
                                    <?= Html::submitButton('Tampilkan', ['class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="content-index"></div>
                        </div>
                    </div>
                </div>

                <br />
                <br />
                <br />

            </div>
          </div>
        </div>
        <?php ActiveForm::end(); ?>

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
        'label' => 'Export Data Kegiatan Rutin',
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
                                'header' => 'Judul',
                                'headerOptions' =>[
                                  'style' => 'width:20%'
                                ],
                                'attribute' => 'title',
                                ],

                                [
                                'header' => 'Deskripsi',
                                'headerOptions' =>[
                                  'style' => 'width:35%'
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
                                'header' => 'Uang Muka',
                                'headerOptions' =>[
                                    'style' => 'width:15%'
                                ],
                                'format'=> 'raw',
                                'value' => function($data)
                                    {
                                        if($data->activityDailyBudgetChiefsOne != null){
                                            return $data->activityDailyBudgetChiefsOne->budget_value_sum;
                                        }elseif ($data->activityDailyBudgetDepartsOne != null) {
                                            return $data->activityDailyBudgetDepartsOne->budget_value_sum;
                                        }elseif($data->activityDailyBudgetSectionsOne != null){
                                            return $data->activityDailyBudgetSectionsOne->budget_value_sum;
                                        }else{
                                            return "-";
                                        }
                                    }
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions' => ['style' => 'width:160px;'],
                                    'header' => 'Actions',
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            if (Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3') {
                                                return Html::a('| <span class="fa fa-eye"></span> |', $url, [
                                                    'title' => Yii::t('app', 'view'),
                                                ]);
                                            }
                                        },
                                    ],

                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        if ($action === 'view') {
                                            $url = Url::to(['activity-daily-report/view', 'id' => $model['id']]);
                                            return $url;
                                        }
                                    },
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
