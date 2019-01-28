<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Budget */

$this->title = 'Buat Data Sumber Dana';
$this->params['breadcrumbs'][] = ['label' => 'Sumber Dana', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
