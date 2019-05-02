<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model common\models\Approve */

$this->title = 'Data Pertangungjawaban';
$this->params['breadcrumbs'][] = ['label' => 'Approves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
function to_rp($val)
{
    return "Rp " . number_format($val,0,',','.');
}
?>
<style>
    .img{
        width: 300px !important;
        height: auto;
        margin-bottom:5px;
    }
</style>
<?php if ($role->role == 7) { ?>
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
                    return to_rp($model->activity->activityBudgetDepartmentsOne->budget_value_sum);
                }
            ],
            [
                'attribute'=>'activity.activityBudgetDepartmentsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan',
                'value' => function($model)
                {
                    return to_rp($model->activity->activityBudgetDepartmentsOne->budget_value_dp);
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
                    return to_rp($model->activity->activityBudgetSectionsOne->budget_value_sum);
                }
            ],
            [
                'attribute'=>'activity.activityBudgetSectionsOne.budget_value_dp',
                'label'=>'Uang Yang Terealisasikan',
                'value' => function($model)
                {
                    return to_rp($model->activity->activityBudgetSectionsOne->budget_value_dp);
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
