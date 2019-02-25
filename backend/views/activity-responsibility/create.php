<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = 'Form Pertanggung Jawaban';
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="approve-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelBudget' => $modelBudget,
        'baru' => $baru,

    ]) ?>

</div>
