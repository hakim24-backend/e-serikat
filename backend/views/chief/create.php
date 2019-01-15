<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Chief */

$this->title = 'Create Chief';
$this->params['breadcrumbs'][] = ['label' => 'Chiefs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chief-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
