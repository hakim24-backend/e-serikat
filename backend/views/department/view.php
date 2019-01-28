<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Department */

$this->title = 'Data Master Departemen';
// $this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
// \yii\web\YiiAsset::register($this);
?>
<div class="department-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'depart_name',
            // 'id_chief',
            // 'status_budget',
            // 'depart_code',
            // 'user_id',

            [
                'attribute'=>'id',
                'label'=>'Id Departemen'
            ],
            [
                'attribute'=>'id_chief',
                'label'=>'Id Ketua'
            ],
            [
                'attribute'=>'depart_name',
                'label'=>'Nama Departemen'
            ],
            [
                'attribute'=>'depart_code',
                'label'=>'Kode Departemen'
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
