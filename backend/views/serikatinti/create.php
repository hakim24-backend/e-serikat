<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Serikatinti */

$this->title = 'Create Serikatinti';
$this->params['breadcrumbs'][] = ['label' => 'Serikatintis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serikatinti-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
