<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = 'Update Data';
// $this->params['breadcrumbs'][] = ['label' => 'Seksi', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="section-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
