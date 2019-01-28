<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Serikatinti */

$this->title = 'Buat Akun Data Master E-Serikat';
$this->params['breadcrumbs'][] = ['label' => 'Serikatinti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serikatinti-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
