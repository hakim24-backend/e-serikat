<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = 'Create Approve';
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="approve-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
