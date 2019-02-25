<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDaily */

$this->title = 'Form Pertanggungjawaban';
$this->params['breadcrumbs'][] = ['label' => 'Activity Dailies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-daily-create">

    <?= $this->render('_form', [
      'model' => $model,
      'modelBudget' => $modelBudget,
      'baru' => $baru,
    ]) ?>

</div>
