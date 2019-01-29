<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Chief */

$this->title = 'Data Master Ketua';
$this->params['breadcrumbs'][] = ['label' => 'Chiefs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="chief-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'chief_name',
            // 'chief_code',
            // 'status_budget',
            // 'user_id',

            [
                'attribute'=>'id',
                'label'=>'Id Ketua'
            ],
            [
                'attribute'=>'chief_name',
                'label'=>'Nama Ketua'
            ],
            [
                'attribute'=>'chief_code',
                'label'=>'Kode Ketua'
            ],
            [
                'attribute'=>'status_budget',
                'label'=>'Status Budget'
            ],
            [
                'attribute'=>'user_id',
                'label'=>'Id User'
            ]
        ],
    ]) ?>

</div>
