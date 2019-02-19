<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Activity */

$this->title = 'Data Kegiatan';
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="activity-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute'=>'name_activity',
                'label'=>'Nama Kegiatan'
            ],
            [
                'attribute'=>'title',
                'label'=>'Judul'
            ],
            [
                'attribute'=>'background',
                'label'=>'Latar Belakang'
            ],
            [
                'attribute'=>'purpose',
                'label'=>'Tujuan'
            ],
            [
                'attribute'=>'target_activity',
                'label'=>'Sasaran Kegiatan'
            ],
            [
                'attribute'=>'place_activity',
                'label'=>'Tempat Pelaksanaan'
            ],
            [
                'attribute'=>'place_activity_x',
                'label'=>'Tempat Pelaksanaan X'
            ],
            [
                'attribute'=>'place_activity_y',
                'label'=>'Tempat Pelaksanaan Y'
            ],
            [
                'attribute'=>'date_start',
                'label'=>'Tanggal Mulai'
            ],
            [
                'attribute'=>'date_end',
                'label'=>'Tanggal Berakhir'
            ],
            // [
            //     'label'=>'Bagian Seksi',
            //     'value' => implode(',',\yii\helpers\ArrayHelper::map($model->activitySections, 'id', 'section_name')),
            // ],
            // [
            //     'label'=>'Nama Anggota Seksi',
            //     'value' => implode(',',\yii\helpers\ArrayHelper::map($model->activitySectionMembers, 'id', 'section_name_member')),
            // ],
            // [
            //     'label'=>'Panitia Inti Ketua',
            //     'value' => implode(',',\yii\helpers\ArrayHelper::map($model->activitySectionMembers, 'id', 'section_name_member')),
            // ],
            // [
            //     'label'=>'Panitia Inti Wakil',
            //     'value' => implode(',',\yii\helpers\ArrayHelper::map($model->activitySectionMembers, 'id', 'section_name_member')),
            // ],
            // [
            //     'label'=>'Panitia Inti Sekretaris',
            //     'value' => implode(',',\yii\helpers\ArrayHelper::map($model->activitySectionMembers, 'id', 'section_name_member')),
            // ],
            // [
            //     'label'=>'Panitia Inti Bendahara',
            //     'value' => implode(',',\yii\helpers\ArrayHelper::map($model->activitySectionMembers, 'id', 'section_name_member')),
            // ],
        ],
    ]) ?>

</div>
