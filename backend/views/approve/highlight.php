<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Form Pertanggungjawaban';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-responsibility-index">

    <p>
        <?= Html::a('Buat Pertanggungjawaban', ['create','id' => $id], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                        <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // 'id',
                            // 'description:ntext',
                            // 'responsibility_value',
                            // 'file:ntext',
                            // 'photo:ntext',
                            //'activity_id',

                            [
                            'header' => 'Deskripsi',
                            'attribute' => 'description',
                            ],
                            [
                            'header' => 'Foto',
                            'attribute' => 'photo',
                            'format'=> 'raw',
                            'value'=>function ($model) {         
                            return Html::img(Yii::$app->request->BaseUrl.'../../web/template/'.$model->photo, ['width'=>'100']);      
                            },              
                            ],
                            [
                            'header' => 'File',
                            'attribute' => 'file',
                            'format'=> 'raw',
                            'value'=>function ($model) {         
                            return Html::a('Download File', ['download', 'id' => $model->id], ['class' => 'btn btn-primary']);      
                            },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '| {view} | {update} | {delete}',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>