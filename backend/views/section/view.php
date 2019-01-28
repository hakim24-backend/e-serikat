<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = 'Data Master Departemen';
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="section-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'section_name',
            // 'id_depart',
            // 'status_budget',
            // 'section_code',
            // 'user_id',

            [
                'attribute'=>'id',
                'label'=>'Id Seksi'
            ],
            [
                'attribute'=>'id_depart',
                'label'=>'Id Departemen'
            ],
            [
                'attribute'=>'section_name',
                'label'=>'Nama Seksi'
            ],
            [
                'attribute'=>'section_code',
                'label'=>'Kode Seksi'
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
