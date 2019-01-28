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

    <p>
        <?= Html::a('Input Budget', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'header' => 'Nilai Rekening',
                                    'attribute' => 'budget_rek',
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
