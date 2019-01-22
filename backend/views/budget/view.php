<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Budget */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Budgets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="budget-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
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
                'label'=>'Nilai Saldo'
            ],
            [
                'attribute'=>'budget_rek',
                'label'=>'Nilai Rekening'
            ]
        ],
    ]) ?>

</div>
