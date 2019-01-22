<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Activity Responsibilities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-responsibility-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Activity Responsibility', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'description:ntext',
            'responsibility_value',
            'file:ntext',
            'photo:ntext',
            //'activity_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>