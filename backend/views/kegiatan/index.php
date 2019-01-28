<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Uang Muka Kegiatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-index">

<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Input Data Uang Muka Kegiatan', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'template' => '| {update} | {view} | {delete} |',

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
