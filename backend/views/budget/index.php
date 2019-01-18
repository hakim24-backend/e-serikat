<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budgets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Input Budget', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            'header' => 'Budget Tahunan',
            'attribute' => 'budget_year',
            ],


            [
            'header' => 'Nama Budget',
            'attribute' => 'budget_name',
            ],

            [
            'header' => 'Nilai Budget',
            'attribute' => 'budget_value',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
