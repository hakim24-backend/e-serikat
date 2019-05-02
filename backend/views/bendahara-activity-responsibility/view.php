<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = 'Data Pertangungjawaban';
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$url = Yii::$app->request->baseUrl."/template/";

\yii\web\YiiAsset::register($this);
?>
<style>
    .img{
        width: 300px !important;
        height: auto;
        margin-bottom:5px;
    }
</style>
<?php if ($role->role == 4) { ?>
<div class="approve-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'activity.activityBudgetSecretariatsOne.budget_value_sum',
                'label'=>'Uang Muka',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetSecretariatsOne->budget_value_sum,0,',','.');
                  }
            ],
            [
                'attribute'=>'activity.activityBudgetSecretariatsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan'
            ],
            [
                'attribute'=>'file',
                'format'=>'raw',
                'value'=> (($model->file != "" )? $model->file." <br>".Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) : "-"),
                'label'=>'File'
            ],
            [
                'attribute'=>'photo',
                'value'=> function($model)
                {
                    $img = explode("**",$model->photo);
                    $raw ="";
                    foreach($img as $m)
                    {
                        $raw .= "<img src='".Yii::$app->request->baseUrl."/template/".$m."' class='img'/> <br>";
                    }
                    return $raw;
                },
                'format' => 'raw',
                'label'=>'Foto'
            ],
            [
                'attribute'=>'activity.name_activity',
                'label'=>'Nama Kegiatan',
                
                
            ],
            [
                'attribute'=>'activity.title',
                'label'=>'Judul Kegiatan'
            ],
            [
                'attribute'=>'activity.background',
                'label'=>'Latar Belakang Kegiatan',
                
            ],
            [
                'attribute'=>'activity.purpose',
                'label'=>'Tujuan Kegiatan',
                
            ],
            [
                'attribute'=>'activity.target_activity',
                'label'=>'Target Kegiatan',
                
            ]
        ],
    ]) ?>
</div>
<?php } elseif ($role->role == 6) { ?>
<div class="approve-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'activity.activityBudgetChiefsOne.budget_value_sum',
                'label'=>'Uang Muka',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetChiefsOne->budget_value_sum,0,',','.');
                  }
            ],
            [
                'attribute'=>'activity.activityBudgetChiefsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetChiefsOne->budget_value_dp,0,',','.');
                  }
            ],
            [
                'attribute'=>'file',
                'format'=>'raw',
                'value'=> (($model->file != "" )? $model->file." <br>".Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) : "-"),
                'label'=>'File'
            ],
            [
                'attribute'=>'photo',
                'value'=> function($model)
                {
                    $img = explode("**",$model->photo);
                    $raw ="";
                    foreach($img as $m)
                    {
                        $raw .= "<img src='".Yii::$app->request->baseUrl."/template/".$m."' class='img'/> <br>";
                    }
                    return $raw;
                },
                'format' => 'raw',
                'label'=>'Foto'
            ],
            [
                'attribute'=>'activity.name_activity',
                'label'=>'Nama Kegiatan',
                
                
            ],
            [
                'attribute'=>'activity.title',
                'label'=>'Judul Kegiatan'
            ],
            [
                'attribute'=>'activity.background',
                'label'=>'Latar Belakang Kegiatan',
                
            ],
            [
                'attribute'=>'activity.purpose',
                'label'=>'Tujuan Kegiatan',
                
            ],
            [
                'attribute'=>'activity.target_activity',
                'label'=>'Target Kegiatan',
                
            ]
        ],
    ]) ?>
</div>
<?php } elseif ($role->role == 7) { ?>
<div class="approve-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'activity.activityBudgetDepartmentsOne.budget_value_sum',
                'label'=>'Uang Muka',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetDepartmentsOne->budget_value_sum,0,',','.');
                  }

            ],
            [
                'attribute'=>'activity.activityBudgetDepartmentsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetDepartmentsOne->budget_value_dp,0,',','.');
                  }
            ],
            [
                'attribute'=>'file',
                'format'=>'raw',
                'value'=> (($model->file != "" )? $model->file." <br>".Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) : "-"),
                'label'=>'File'
            ],
            [
                'attribute'=>'photo',
                'value'=> function($model)
                {
                    $img = explode("**",$model->photo);
                    $raw ="";
                    foreach($img as $m)
                    {
                        $raw .= "<img src='".Yii::$app->request->baseUrl."/template/".$m."' class='img'/> <br>";
                    }
                    return $raw;
                },
                'format' => 'raw',
                'label'=>'Foto'
            ],
            [
                'attribute'=>'activity.name_activity',
                'label'=>'Nama Kegiatan',
                
                
            ],
            [
                'attribute'=>'activity.title',
                'label'=>'Judul Kegiatan'
            ],
            [
                'attribute'=>'activity.background',
                'label'=>'Latar Belakang Kegiatan',
                
            ],
            [
                'attribute'=>'activity.purpose',
                'label'=>'Tujuan Kegiatan',
                
            ],
            [
                'attribute'=>'activity.target_activity',
                'label'=>'Target Kegiatan',
                
            ]
        ],
    ]) ?>
</div>
<?php } elseif ($role->role == 8) { ?>
<div class="approve-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'description',
                'label'=>'Deskripsi'
            ],
            [
                'attribute'=>'activity.activityBudgetSectionsOne.budget_value_sum',
                'label'=>'Uang Muka',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetSectionsOne->budget_value_sum,0,',','.');
                  }

            ],
            [
                'attribute'=>'activity.activityBudgetSectionsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan',
                'value' => function($model)
                  {
                    return "Rp " . number_format($model->activity->activityBudgetSectionsOne->budget_value_dp,0,',','.');
                  }
            ],
            [
                'attribute'=>'file',
                'format'=>'raw',
                'value'=> (($model->file != "" )? $model->file." <br>".Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']) : "-"),
                'label'=>'File'
            ],
            [
                'attribute'=>'photo',
                'value'=> function($model)
                {
                    $img = explode("**",$model->photo);
                    $raw ="";
                    foreach($img as $m)
                    {
                        $raw .= "<img src='".Yii::$app->request->baseUrl."/template/".$m."' class='img'/> <br>";
                    }
                    return $raw;
                },
                'format' => 'raw',
                'label'=>'Foto'
            ],
            [
                'attribute'=>'activity.name_activity',
                'label'=>'Nama Kegiatan',
                
                
            ],
            [
                'attribute'=>'activity.title',
                'label'=>'Judul Kegiatan'
            ],
            [
                'attribute'=>'activity.background',
                'label'=>'Latar Belakang Kegiatan',
                
            ],
            [
                'attribute'=>'activity.purpose',
                'label'=>'Tujuan Kegiatan',
                
            ],
            [
                'attribute'=>'activity.target_activity',
                'label'=>'Target Kegiatan',
                
            ]
        ],
    ]) ?>
</div>
<?php } ?>
