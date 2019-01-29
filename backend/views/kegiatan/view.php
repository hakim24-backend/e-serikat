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
            'title',
            'background:ntext',
            'purpose:ntext',
            'target_activity:ntext',
            'place_activity:ntext',
            'place_activity_x:ntext',
            'place_activity_y:ntext',
            'date_start',
            'date_end',
            'role',
            'finance_status',
            'department_status',
            'chief_status',
            'chief_code_id',
            'department_code_id',
            'done',
        ],
    ]) ?>

</div>
