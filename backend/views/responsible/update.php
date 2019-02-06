<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityResponsibility */

$this->title = 'Update Activity Responsibility: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Activity Responsibilities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="activity-responsibility-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
