<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityResponsibility */

$this->title = 'Form Pertanggung Jawaban';
$this->params['breadcrumbs'][] = ['label' => 'Activity Responsibilities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-responsibility-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelBudget' => $modelBudget,
        'baru' => $baru,
    ]) ?>

</div>
