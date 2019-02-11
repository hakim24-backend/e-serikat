<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDaily */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Activity Dailies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="activity-daily-view">

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
            'finance_status',
            'department_status',
            'chief_status',
            'chief_code_id',
            'department_code_id',
            'title',
            'description:ntext',
            'role',
            'date_start',
            'date_end',
            'done',
        ],
    ]) ?>

</div>