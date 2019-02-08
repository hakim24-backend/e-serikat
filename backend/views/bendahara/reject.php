<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDailyReject */

$this->title = 'Create Activity Daily Reject';
$this->params['breadcrumbs'][] = ['label' => 'Activity Daily Rejects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-reject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
