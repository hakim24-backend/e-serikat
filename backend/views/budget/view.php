<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Budget */

$this->title = 'Update Sumber Dana';
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="budget-view">

    <p>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            // 'budget_code',
            // 'budget_year',
            // 'budget_name',
            // 'budget_value',

            [
                'attribute'=>'budget_code',
                'label'=>'Kode Budget'
            ],
            [
                'attribute'=>'budget_year',
                'label'=>'Tahun Budget'
            ],
            [
                'attribute'=>'budget_name',
                'label'=>'Nama Budget'
            ],
            [
                'attribute'=>'budget_value',
                'label'=>'Nilai Saldo',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->budget_value,0,',','.');
                  }
            ],
            [
                'attribute'=>'budget_rek',
                'label'=>'Nilai Rekening'
            ]
        ],
    ]) ?>

</div>
