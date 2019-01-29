<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Serikatinti */

$this->title = 'Daftar User';
$this->params['breadcrumbs'][] = ['label' => 'Serikatinti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="serikatinti-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => 'Belum Terisi'],
        'attributes' => [
            // 'id',
            'name_role',
            // 'usersOne.name',
            [
                'attribute'=>'usersOne.name',
                'label'=>'Nama User'
            ]
        ],
    ]) ?>

</div>
