<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">

    <p>
        <?= Html::a('Buat Data Kegiatan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
                                    // 'title',
                                    // 'background:ntext',
                                    // 'purpose:ntext',
                                    // 'target_activity:ntext',
                                //'place_activity:ntext',
                                //'place_activity_x:ntext',
                                //'place_activity_y:ntext',
                                //'date_start',
                                //'date_end',
                                //'role',
                                //'finance_status',
                                //'department_status',
                                //'chief_status',
                                //'chief_code_id',
                                //'department_code_id',
                                //'done',

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
                                'header' => 'Target Kegiatan',
                                'attribute' => 'target_activity',
                                ],
                                [
                                'header' => 'Tempat Kegiatan',
                                'attribute' => 'place_activity',
                                ],
                                // [
                                // 'header' => 'Tempak Kegiatan X',
                                // 'attribute' => 'place_activity_x',
                                // ],
                                // [
                                // 'header' => 'Tempat Kegiatan Y',
                                // 'attribute' => 'place_activity_y',
                                // ],
                                [
                                'header' => 'Tangal Mulai',
                                'attribute' => 'date_start',
                                ],
                                [
                                'header' => 'Tanggal Berkahir',
                                'attribute' => 'date_end',
                                ],
                                // [
                                // 'header' => 'Status Anggaran',
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
                                // 'header' => 'Id Kode Ketua',
                                // 'attribute' => 'chief_code_id',
                                // ],
                                // [
                                // 'header' => 'Id Kode Departemen',
                                // 'attribute' => 'department_code_id',
                                // ],
                                // [
                                // 'header' => 'Done',
                                // 'attribute' => 'done',
                                // ],


                                ['class' => 'yii\grid\ActionColumn',
                                 'header' => 'Action',
                                 'template' => '| {update} | {view} | {delete} |',
                                ]
                            ],
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
