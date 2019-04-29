<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pemindahan Dana';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-index">

	<p>
        <?= Html::a('Buat Pemindahan Dana', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-body">
            <div class="table-responsive">
                <?php Pjax::begin(); ?>
                <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => 'Kode Asal',
                                'attribute' => 'code_source',
                            ],
                            [
                                'header' => 'Kode Tujuan',
                                'attribute' => 'code_dest',
                            ],
                            [
                                'header' => 'Nilai Anggaran',
                                'attribute' => 'value',
                                'value' => function($model)
                                  {
                                    return "Rp " . number_format($model->value,0,',','.');
                                  }
                            ],
                        ],
                    ]);
                ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
